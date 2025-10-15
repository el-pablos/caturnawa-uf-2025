<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sponsor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SponsorController extends Controller
{
    /**
     * Display a listing of sponsors
     */
    public function index()
    {
        $sponsors = Sponsor::orderBy('order', 'asc')->paginate(20);
        
        return view('admin.sponsors.index', compact('sponsors'));
    }

    /**
     * Show the form for creating a new sponsor
     */
    public function create()
    {
        return view('admin.sponsors.create');
    }

    /**
     * Store a newly created sponsor in storage
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'website' => 'nullable|url|max:255',
            'type' => 'required|in:platinum,gold,silver,bronze,media_partner',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('sponsors', 'public');
        }

        Sponsor::create([
            'name' => $request->name,
            'logo' => $logoPath,
            'website' => $request->website,
            'type' => $request->type,
            'order' => $request->order,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()->route('admin.sponsors.index')
            ->with('success', 'Sponsor created successfully.');
    }

    /**
     * Show the form for editing the specified sponsor
     */
    public function edit(Sponsor $sponsor)
    {
        return view('admin.sponsors.edit', compact('sponsor'));
    }

    /**
     * Update the specified sponsor in storage
     */
    public function update(Request $request, Sponsor $sponsor)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'website' => 'nullable|url|max:255',
            'type' => 'required|in:platinum,gold,silver,bronze,media_partner',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = [
            'name' => $request->name,
            'website' => $request->website,
            'type' => $request->type,
            'order' => $request->order,
            'is_active' => $request->has('is_active') ? true : false,
        ];

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($sponsor->logo && Storage::disk('public')->exists($sponsor->logo)) {
                Storage::disk('public')->delete($sponsor->logo);
            }
            
            $data['logo'] = $request->file('logo')->store('sponsors', 'public');
        }

        $sponsor->update($data);

        return redirect()->route('admin.sponsors.index')
            ->with('success', 'Sponsor updated successfully.');
    }

    /**
     * Remove the specified sponsor from storage
     */
    public function destroy(Sponsor $sponsor)
    {
        // Delete logo file if exists
        if ($sponsor->logo && Storage::disk('public')->exists($sponsor->logo)) {
            Storage::disk('public')->delete($sponsor->logo);
        }

        $sponsor->delete();

        return redirect()->route('admin.sponsors.index')
            ->with('success', 'Sponsor deleted successfully.');
    }
}

