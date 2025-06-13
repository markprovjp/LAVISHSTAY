<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Translation;
use App\Models\TableTranslation;
use App\Models\Language;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;  

class TranslationController extends Controller
{
    public function index(Request $request){
        $perPage = 6;
        $page = $request->input('page', 1);
        $tableTranslations = DB::table('table_translation')->where('is_active', 1)->get(['table_name', 'display_name'])->toArray();
        $tables = array_column($tableTranslations, 'table_name');
        $displayNames = array_column($tableTranslations, 'display_name', 'table_name'); // Map table_name -> display_name
        $tables = array_filter($tables, function ($table) {
            return !in_array($table, ['migrations', 'users', 'password_resets', 'translations', 'failed_jobs', 'sessions']);
        });
        $translations = Translation::all();
        $activeTables = session()->get('active_tables', collect($tables));
        $totalTables = count($tables);
        $tables = array_slice($tables, ($page - 1) * $perPage, $perPage);

        // Lấy 5 bản dịch gần đây nhất, thay table_name bằng display_name
        $recentTranslations = Translation::orderBy('translation_id', 'desc')->limit(5)->get()->map(function ($translation) use ($displayNames) {
            $translation->table_name = $displayNames[$translation->table_name] ?? $translation->table_name;
            return $translation;
        });

        $totalTranslations = Translation::count();

        if ($request->ajax() && $request->input('ajax') == 1) {
            $cards = view('admin.translation._cards', compact('tables', 'activeTables', 'translations', 'displayNames'))->render();
            $pagination = view('admin.translation._pagination', compact('page', 'perPage', 'totalTables'))->render();
            return response()->json(['cards' => $cards, 'pagination' => $pagination]);
        }

        return view('admin.translation.index', compact('tables', 'activeTables', 'translations', 'page', 'perPage', 'totalTables', 'recentTranslations', 'totalTranslations', 'displayNames'));
    }

    public function show($table)
    {
        $translations = Translation::where('table_name', $table)->get();
        $languages = Language::all();
        return view('admin.translation.show', compact('table', 'translations', 'languages'));
    }

    public function create()
    {
        $languages = Language::all();
        return view('admin.translation.create', compact('languages'));
    }

    public function store(Request $request)
    {
        Translation::create([
            'table_name' => $request->input('table_name'),
            'column_name' => $request->input('column_name'),
            'record_id' => $request->input('record_id'),
            'language_code' => $request->input('language_code'),
            'value' => $request->input('value'),
        ]);

        return redirect()->route('admin.translation.index')->with('success', 'Translation created successfully');
    }

    public function update(Request $request, $translationId)
    {
        Translation::where('translation_id', $translationId)->update([
            'value' => $request->input('value'),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.translation.index')->with('success', 'Translation updated successfully');
    }

    public function destroyByLanguage($translationId, $languageCode)
    {
        try {
            Log::info('Attempting to delete translation by language', [
                'translation_id' => $translationId,
                'language_code' => $languageCode
            ]);

            $baseTranslation = Translation::find($translationId);
            if (!$baseTranslation) {
                Log::warning('Base translation not found', ['translation_id' => $translationId]);
                return response()->json(['success' => false, 'error' => 'Bản dịch cơ bản không tồn tại'], 404);
            }

            $translation = Translation::where('table_name', $baseTranslation->table_name)
                ->where('column_name', $baseTranslation->column_name)
                ->where('record_id', $baseTranslation->record_id)
                ->where('language_code', $languageCode)
                ->first();

            if (!$translation) {
                $allTranslations = Translation::where('table_name', $baseTranslation->table_name)
                    ->where('column_name', $baseTranslation->column_name)
                    ->where('record_id', $baseTranslation->record_id)
                    ->get()
                    ->toArray();
                Log::warning('Translation not found', [
                    'translation_id' => $translationId,
                    'language_code' => $languageCode,
                    'all_translations' => $allTranslations
                ]);
                return response()->json([
                    'success' => false,
                    'error' => 'Bản dịch không tồn tại cho ngôn ngữ này. Kiểm tra lại language_code.',
                    'debug' => ['available_languages' => array_column($allTranslations, 'language_code')]
                ], 404);
            }

            $translation->delete();
            Log::info('Translation deleted successfully by language', [
                'translation_id' => $translation->translation_id,
                'language_code' => $languageCode
            ]);
            return response()->json(['success' => true, 'message' => 'Xóa bản dịch thành công']);
        } catch (\Exception $e) {
            Log::error('Error in destroyByLanguage: ' . $e->getMessage(), [
                'translation_id' => $translationId,
                'language_code' => $languageCode,
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['success' => false, 'error' => 'Đã xảy ra lỗi server: ' . $e->getMessage()], 500);
        }
    }

    public function destroyRecord($table, $recordId)
    {
        try {
            Log::info('Attempting to delete all translations for record', ['table' => $table, 'record_id' => $recordId]);

            if (!class_exists(Translation::class)) {
                Log::error('Translation model not found');
                return response()->json(['success' => false, 'error' => 'Model Translation không tồn tại'], 500);
            }

            if (!Schema::hasTable('translation')) {
                Log::error('Translation table not found');
                return response()->json(['success' => false, 'error' => 'Bảng translation không tồn tại'], 500);
            }

            $deleted = Translation::where([
                'table_name' => $table,
                'record_id' => $recordId
            ])->delete();

            if ($deleted === 0) {
                Log::warning('No translations found to delete', ['table' => $table, 'record_id' => $recordId]);
                return response()->json(['success' => false, 'error' => 'Không tìm thấy bản dịch để xóa']);
            }

            Log::info('All translations deleted successfully', ['table' => $table, 'record_id' => $recordId, 'deleted_count' => $deleted]);
            return response()->json(['success' => true, 'message' => 'Xóa tất cả bản dịch thành công']);
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Database error in destroyRecord: ' . $e->getMessage(), ['table' => $table, 'record_id' => $recordId]);
            return response()->json(['success' => false, 'error' => 'Lỗi cơ sở dữ liệu: ' . $e->getMessage()], 500);
        } catch (\Exception $e) {
            Log::error('General error in destroyRecord: ' . $e->getMessage(), ['table' => $table, 'record_id' => $recordId]);
            return response()->json(['success' => false, 'error' => 'Đã xảy ra lỗi server: ' . $e->getMessage()], 500);
        }
    }

    public function translate()
    {
        $tables = DB::select('SHOW TABLES');
        $tables = array_map('current', $tables);

        $translations = Translation::all();

        return view('admin.translation.translate', compact('tables', 'translations'));
    }

    public function storeForTable(Request $request, $table)
    {
        try {
            Log::info('Request data:', $request->all());

            $columns = DB::select("SHOW KEYS FROM `$table` WHERE Key_name = 'PRIMARY'");
            $primaryKey = $columns[0]->Column_name ?? 'id';

            // Lấy dữ liệu từ request (hỗ trợ cả JSON và FormData)
            $data = $request->all();

            $request->validate([
                'column_name' => 'required|string',
                'record_id' => 'required|integer|exists:' . $table . ',' . $primaryKey,
                'language_code' => 'required|string|exists:language,language_code',
                'value' => 'nullable|string',
            ], [
                'record_id.exists' => 'ID bản ghi không tồn tại trong bảng ' . $table,
                'language_code.exists' => 'Mã ngôn ngữ không hợp lệ',
            ]);

            // Kiểm tra xem đã có bản ghi với cùng table_name, column_name, record_id, và language_code chưa
            $existingTranslation = Translation::where('table_name', $table)
                ->where('column_name', $data['column_name'])
                ->where('record_id', $data['record_id'])
                ->where('language_code', $data['language_code'])
                ->first();

            if ($existingTranslation) {
                return response()->json(['error' => 'Bản dịch cho record_id, column_name, và language_code này đã tồn tại. Vui lòng sử dụng chức năng sửa để cập nhật.'], 422);
            }

            // Tạo bản ghi mới nếu không trùng lặp
            $translation = Translation::create([
                'table_name' => $table,
                'column_name' => $data['column_name'],
                'record_id' => $data['record_id'],
                'language_code' => $data['language_code'],
                'value' => $data['value'],
            ]);

            return response()->json(['success' => true, 'message' => 'Bản dịch đã được thêm', 'table' => $table]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error in storeForTable: ' . $e->getMessage(), $e->validator->errors()->toArray());
            return response()->json(['error' => $e->validator->errors()->first()], 422);
        } catch (\Exception $e) {
            Log::error('Error in storeForTable: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'Đã xảy ra lỗi server: ' . $e->getMessage()], 500);
        }
    }

    public function updateValue(Request $request, $translationId)
    {
        try {
            $translation = Translation::findOrFail($translationId);
            $request->validate([
                'value' => 'nullable|string|max:255',
            ]);

            $translation->update([
                'value' => $request->input('value'),
                'updated_at' => now(),
            ]);

            return response()->json(['success' => true, 'message' => 'Bản dịch đã được cập nhật']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => $e->validator->errors()->first()], 422);
        } catch (\Exception $e) {
            \Log::error('Error in updateValue: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'Đã xảy ra lỗi server'], 500);
        }
    }

    public function getTranslationValue(Request $request, $table, $column, $recordId, $languageCode)
    {
        try {
            \Log::info('Fetching translation', [
                'table' => $table,
                'column' => $column,
                'recordId' => $recordId,
                'languageCode' => $languageCode
            ]);

            $translation = Translation::where([
                'table_name' => $table,
                'column_name' => $column,
                'record_id' => $recordId,
                'language_code' => $languageCode
            ])->first();

            if ($translation) {
                \Log::info('Translation found', ['translation' => $translation->toArray()]);
                return response()->json([
                    'success' => true,
                    'value' => $translation->value,
                    'translation_id' => $translation->translation_id
                ]);
            } else {
                \Log::warning('No translation found');
                return response()->json([
                    'success' => false,
                    'value' => null,
                    'translation_id' => null
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Error in getTranslationValue: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'value' => null,
                'translation_id' => null,
                'error' => 'Server error'
            ], 500);
        }
    }

    public function manageTables(Request $request)
    {
        $perPage = 10;
        $tables = TableTranslation::select('table_name', 'display_name', 'is_active')
            ->paginate($perPage);

        return view('admin.translation.manage-tables', compact('tables'));
    }

    public function toggleTableStatus(Request $request, $table)
    {
        try {
            $request->validate([
                'is_active' => 'required|boolean',
            ]);

            $activeTables = session()->get('active_tables', collect());
            if ($request->is_active) {
                $activeTables->push($table);
            } else {
                $activeTables = $activeTables->filter(function ($t) use ($table) {
                    return $t !== $table;
                });
            }
            session()->put('active_tables', $activeTables->unique());

            return response()->json(['success' => true, 'message' => 'Trạng thái đã được cập nhật']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'error' => $e->validator->errors()->first()], 422);
        } catch (\Exception $e) {
            \Log::error('Error in toggleTableStatus: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => 'Đã xảy ra lỗi server'], 500);
        }
    }

    public function toggleTableStatusInTable($table, Request $request)
    {
        try {
            $tableRecord = TableTranslation::where('table_name', $table)->firstOrFail();
            $tableRecord->is_active = !$tableRecord->is_active;
            $tableRecord->save();

            return response()->json(['success' => true, 'message' => 'Trạng thái bảng đã được cập nhật']);
        } catch (\Exception $e) {
            \Log::error('Error toggling table status: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => 'Không thể cập nhật trạng thái. Lỗi: ' . $e->getMessage()], 500);
        }
    }

    public function destroyTable($table)
    {
        try {
            $tableRecord = TableTranslation::where('table_name', $table)->firstOrFail();
            $tableRecord->delete();
            return redirect()->route('admin.translation.manage-tables')
                ->with('success', 'Bảng đã được xóa thành công');
        } catch (\Exception $e) {
            Log::error('Error deleting table: ' . $e->getMessage());
            return redirect()->route('admin.translation.manage-tables')
                ->with('error', 'Không thể xóa bảng');
        }
    }

    public function getTables(Request $request)
    {
        try {
            $tables = DB::select('SHOW TABLES');
            $tableNames = array_map('current', $tables);
            $existingTables = TableTranslation::pluck('table_name')->toArray();
            $availableTables = array_diff($tableNames, $existingTables);

            $availableTables = array_values($availableTables);

            return response()->json(['success' => true, 'tables' => $availableTables]);
        } catch (\Exception $e) {
            Log::error('Error fetching tables: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => 'Không thể lấy danh sách bảng'], 500);
        }
    }

    public function storeTable(Request $request)
    {
        try {
            Log::info('Request data for storeTable:', $request->all());

            $validatedData = $request->validate([
                'table_name' => 'required|string|unique:table_translation,table_name',
                'display_name' => 'required|string|max:255',
                'is_active' => 'required|boolean',
            ], [
                'table_name.unique' => 'Tên bảng đã tồn tại trong hệ thống.',
            ]);

            $table = TableTranslation::create([
                'table_name' => $validatedData['table_name'],
                'display_name' => $validatedData['display_name'],
                'is_active' => $validatedData['is_active'],
            ]);

            return response()->json(['success' => true, 'message' => 'Bảng đã được thêm thành công']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error in storeTable: ' . $e->getMessage(), $e->validator->errors()->toArray());
            return response()->json(['error' => $e->validator->errors()->first()], 422);
        } catch (\Exception $e) {
            Log::error('Error in storeTable: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'Đã xảy ra lỗi server: ' . $e->getMessage()], 500);
        }
    }
}