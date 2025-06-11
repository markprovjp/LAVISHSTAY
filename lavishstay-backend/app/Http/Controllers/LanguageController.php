<?php

namespace App\Http\Controllers;

use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LanguageController extends Controller
{
    public function index()
    {
        $languages = Language::paginate(10);
        return view('admin.multinational.languages.index', compact('languages'));
    }

    public function create()
    {
        return view('admin.multinational.languages.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'language_code' => 'required|string|unique:language,language_code|max:10',
            'name' => 'required|string|max:50' 
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Language::create([
            'language_code' => $request->language_code,
            'name' => $request->name
        ]);

        return redirect()->route('admin.multinational.languages')
            ->with('success', 'Language đã được tạo thành công!');
    }

    public function edit($language_code)
    {
        $language = Language::findOrFail($language_code);
        if (!$language) {
            return redirect()->route('admin.multinational.languages')
                ->with('error', 'Language không tồn tại!');
        }
        return view('admin.multinational.languages.edit', compact('language'));
    }

    public function update(Request $request, $language_code)
    {
        $validator = Validator::make($request->all(), [
            'language_code' => 'required|string|unique:language,language_code,' . $language_code . ',language_code|max:10',
            'name' => 'required|string|max:50'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $language = Language::findOrFail($language_code);
        $language->update([
            'language_code' => $request->language_code,
            'name' => $request->name
        ]);

        return redirect()->route('admin.multinational.languages')
            ->with('success', 'Language đã được cập nhật thành công!');
    }

    public function destroy($language_code)
    {
        \Log::info('Deleting Language with language_code: ' . $language_code);
        
        $language = Language::find($language_code);
        
        if (!$language) {
            return redirect()->route('admin.multinational.languages')
                ->with('error', 'Language không tồn tại!');
        }
        
        $language->delete();
        
        \Log::info('Language deleted successfully');
        
        return redirect()->route('admin.multinational.languages')
            ->with('success', 'Language đã được xóa thành công!');
    }
}