<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FaqController extends Controller
{
    /**
     * Display a listing of FAQs
     */
    public function index()
    {
        $faqs = Faq::orderBy('order', 'asc')->paginate(20);
        
        return view('admin.faqs.index', compact('faqs'));
    }

    /**
     * Show the form for creating a new FAQ
     */
    public function create()
    {
        return view('admin.faqs.create');
    }

    /**
     * Store a newly created FAQ in storage
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'question' => 'required|string|max:500',
            'question_en' => 'nullable|string|max:500',
            'question_id' => 'nullable|string|max:500',
            'answer' => 'required|string|max:2000',
            'answer_en' => 'nullable|string|max:2000',
            'answer_id' => 'nullable|string|max:2000',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Faq::create([
            'question' => $request->question,
            'question_en' => $request->question_en,
            'question_id' => $request->question_id,
            'answer' => $request->answer,
            'answer_en' => $request->answer_en,
            'answer_id' => $request->answer_id,
            'order' => $request->order,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()->route('admin.faqs.index')
            ->with('success', 'FAQ created successfully.');
    }

    /**
     * Show the form for editing the specified FAQ
     */
    public function edit(Faq $faq)
    {
        return view('admin.faqs.edit', compact('faq'));
    }

    /**
     * Update the specified FAQ in storage
     */
    public function update(Request $request, Faq $faq)
    {
        $validator = Validator::make($request->all(), [
            'question' => 'required|string|max:500',
            'question_en' => 'nullable|string|max:500',
            'question_id' => 'nullable|string|max:500',
            'answer' => 'required|string|max:2000',
            'answer_en' => 'nullable|string|max:2000',
            'answer_id' => 'nullable|string|max:2000',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $faq->update([
            'question' => $request->question,
            'question_en' => $request->question_en,
            'question_id' => $request->question_id,
            'answer' => $request->answer,
            'answer_en' => $request->answer_en,
            'answer_id' => $request->answer_id,
            'order' => $request->order,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()->route('admin.faqs.index')
            ->with('success', 'FAQ updated successfully.');
    }

    /**
     * Remove the specified FAQ from storage
     */
    public function destroy(Faq $faq)
    {
        $faq->delete();

        return redirect()->route('admin.faqs.index')
            ->with('success', 'FAQ deleted successfully.');
    }

    /**
     * Reorder FAQs
     */
    public function reorder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'items' => 'required|array',
            'items.*.id' => 'required|exists:faqs,id',
            'items.*.order' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Invalid data'], 422);
        }

        foreach ($request->items as $item) {
            Faq::where('id', $item['id'])->update(['order' => $item['order']]);
        }

        return response()->json(['success' => true, 'message' => 'FAQs reordered successfully']);
    }
}

