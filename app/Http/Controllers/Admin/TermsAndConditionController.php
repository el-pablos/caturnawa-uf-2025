<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TermsAndCondition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TermsAndConditionController extends Controller
{
    /**
     * Display a listing of terms and conditions
     */
    public function index()
    {
        $terms = TermsAndCondition::orderBy('order', 'asc')->paginate(20);
        
        return view('admin.terms-and-conditions.index', compact('terms'));
    }

    /**
     * Show the form for creating a new term
     */
    public function create()
    {
        return view('admin.terms-and-conditions.create');
    }

    /**
     * Store a newly created term in storage
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:general,competition,privacy,payment',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        TermsAndCondition::create([
            'title' => $request->title,
            'content' => $request->content,
            'type' => $request->type,
            'order' => $request->order,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()->route('admin.terms-and-conditions.index')
            ->with('success', 'Terms and Conditions created successfully.');
    }

    /**
     * Show the form for editing the specified term
     */
    public function edit(TermsAndCondition $termsAndCondition)
    {
        return view('admin.terms-and-conditions.edit', compact('termsAndCondition'));
    }

    /**
     * Update the specified term in storage
     */
    public function update(Request $request, TermsAndCondition $termsAndCondition)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:general,competition,privacy,payment',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $termsAndCondition->update([
            'title' => $request->title,
            'content' => $request->content,
            'type' => $request->type,
            'order' => $request->order,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()->route('admin.terms-and-conditions.index')
            ->with('success', 'Terms and Conditions updated successfully.');
    }

    /**
     * Remove the specified term from storage
     */
    public function destroy(TermsAndCondition $termsAndCondition)
    {
        $termsAndCondition->delete();

        return redirect()->route('admin.terms-and-conditions.index')
            ->with('success', 'Terms and Conditions deleted successfully.');
    }
}

