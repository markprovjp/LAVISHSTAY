<?php

namespace App\Http\Controllers;

use App\Models\FAQ;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FAQController extends Controller
{
    public function index()
    {
        $faqs = FAQ::ordered()->paginate(10);
        return view('admin.faqs.index', compact('faqs'));
    }

    public function create()
    {
        return view('admin.faqs.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'question_en' => 'required|string',
            'question_vi' => 'required|string',
            'answer_en' => 'required|string',
            'answer_vi' => 'required|string',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        FAQ::create([
            'question_en' => $request->question_en,
            'question_vi' => $request->question_vi,
            'answer_en' => $request->answer_en,
            'answer_vi' => $request->answer_vi,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->route('admin.faqs')
            ->with('success', 'FAQ đã được tạo thành công!');
    }

    public function show(FAQ $faq)
    {
        return view('admin.faqs.show', compact('faq'));
    }

    public function edit($faqId)
    {
        $faq = FAQ::findOrFail($faqId);
        // dd($faq); 
        if (!$faq) {
            return redirect()->route('admin.faqs')
                ->with('error', 'FAQ không tồn tại!');
        }
        return view('admin.faqs.edit', compact('faq'));
    }

    public function update(Request $request, $faqId)
    {
        $validator = Validator::make($request->all(), [
            'question_en' => 'required|string',
            'question_vi' => 'required|string',
            'answer_en' => 'required|string',
            'answer_vi' => 'required|string',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $faq = FAQ::findOrFail($faqId);
        $faq->update([
            'question_en' => $request->question_en,
            'question_vi' => $request->question_vi,
            'answer_en' => $request->answer_en,
            'answer_vi' => $request->answer_vi,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->route('admin.faqs')
            ->with('success', 'FAQ đã được cập nhật thành công!');
    }

    public function destroy($faqId)
    {
        // Debug để xem có nhận được ID không
        \Log::info('Deleting FAQ with ID: ' . $faqId);
        
        $faq = FAQ::find($faqId);
        
        if (!$faq) {
            return redirect()->route('admin.faqs')
                ->with('error', 'FAQ không tồn tại!');
        }
        
        $faq->delete();
        
        // Debug để xem có xóa thành công không
        \Log::info('FAQ deleted successfully');
        
        return redirect()->route('admin.faqs')
            ->with('success', 'FAQ đã được xóa thành công!');
    }


    public function toggleStatus(FAQ $faq)
    {
        $faq->update(['is_active' => !$faq->is_active]);
        
        return response()->json([
            'success' => true,
            'message' => 'Trạng thái FAQ đã được cập nhật!',
            'is_active' => $faq->is_active
        ]);
    }
}