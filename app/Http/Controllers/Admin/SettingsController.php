<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * Display settings page
     */
    public function index()
    {
        $settings = [];
        $allSettings = Setting::all();

        foreach ($allSettings as $setting) {
            $settings[$setting->key] = Setting::get($setting->key);
        }

        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update settings
     */
    public function update(Request $request)
    {
        $request->validate([
            'maintenance_mode' => 'boolean',
            'maintenance_message' => 'required|string|max:500',
            'registration_open' => 'boolean',
            'registration_closed_message' => 'required|string|max:500',
            'site_name' => 'required|string|max:255',
            'site_description' => 'required|string|max:500',
        ]);

        try {
            // Update each setting
            Setting::set('maintenance_mode', $request->has('maintenance_mode'), 'boolean', 'Enable/disable maintenance mode');
            Setting::set('maintenance_message', $request->maintenance_message, 'string', 'Message to show when maintenance mode is enabled');
            Setting::set('registration_open', $request->has('registration_open'), 'boolean', 'Enable/disable user registration');
            Setting::set('registration_closed_message', $request->registration_closed_message, 'string', 'Message to show when registration is closed');
            Setting::set('site_name', $request->site_name, 'string', 'Website name');
            Setting::set('site_description', $request->site_description, 'string', 'Website description');

            return back()->with('success', 'Pengaturan berhasil disimpan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menyimpan pengaturan: ' . $e->getMessage());
        }
    }

    /**
     * Toggle maintenance mode
     */
    public function toggleMaintenance()
    {
        try {
            $currentMode = Setting::isMaintenanceMode();
            Setting::set('maintenance_mode', !$currentMode, 'boolean');

            $message = $currentMode ? 'Mode maintenance dinonaktifkan.' : 'Mode maintenance diaktifkan.';
            
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'maintenance_mode' => !$currentMode
                ]);
            }

            return back()->with('success', $message);
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengubah mode maintenance: ' . $e->getMessage()
                ]);
            }
            return back()->with('error', 'Gagal mengubah mode maintenance: ' . $e->getMessage());
        }
    }

    /**
     * Toggle registration
     */
    public function toggleRegistration()
    {
        try {
            $currentStatus = Setting::isRegistrationOpen();
            Setting::set('registration_open', !$currentStatus, 'boolean');

            $message = $currentStatus ? 'Pendaftaran ditutup.' : 'Pendaftaran dibuka.';
            
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'registration_open' => !$currentStatus
                ]);
            }

            return back()->with('success', $message);
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengubah status pendaftaran: ' . $e->getMessage()
                ]);
            }
            return back()->with('error', 'Gagal mengubah status pendaftaran: ' . $e->getMessage());
        }
    }
}
