<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactInformation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContactInformationController extends Controller
{
    /**
     * Display the contact information (single record)
     */
    public function index()
    {
        $contact = ContactInformation::first();
        
        return view('admin.contact-information.index', compact('contact'));
    }

    /**
     * Show the form for editing contact information
     */
    public function edit()
    {
        $contact = ContactInformation::firstOrFail();
        
        return view('admin.contact-information.edit', compact('contact'));
    }

    /**
     * Update the contact information
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
            'whatsapp' => 'required|string|max:20',
            'instagram' => 'nullable|string|max:100',
            'tiktok' => 'nullable|string|max:100',
            'youtube' => 'nullable|string|max:100',
            'address' => 'required|string|max:500',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $contact = ContactInformation::first();
        
        if (!$contact) {
            $contact = new ContactInformation();
        }

        $contact->update([
            'email' => $request->email,
            'whatsapp' => $request->whatsapp,
            'instagram' => $request->instagram,
            'tiktok' => $request->tiktok,
            'youtube' => $request->youtube,
            'address' => $request->address,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()->route('admin.contact-information.index')
            ->with('success', 'Contact information updated successfully.');
    }
}

