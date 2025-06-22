<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

/**
 * Controller untuk pengaturan sistem
 * 
 * Mengelola konfigurasi aplikasi dan pengaturan global
 */
class SettingController extends Controller
{
    /**
     * Tampilkan halaman pengaturan
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $settings = $this->getSettings();
        
        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update pengaturan sistem
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // General Settings
            'app_name' => 'required|string|max:255',
            'app_description' => 'nullable|string|max:1000',
            'app_logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'app_favicon' => 'nullable|image|mimes:ico,png|max:512',
            
            // Contact Information
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'required|string|max:20',
            'contact_address' => 'nullable|string|max:500',
            
            // Social Media
            'social_facebook' => 'nullable|url|max:255',
            'social_instagram' => 'nullable|url|max:255',
            'social_twitter' => 'nullable|url|max:255',
            'social_youtube' => 'nullable|url|max:255',
            
            // Payment Settings
            'midtrans_server_key' => 'required|string|max:255',
            'midtrans_client_key' => 'required|string|max:255',
            'midtrans_is_production' => 'boolean',
            
            // Email Settings
            'mail_driver' => 'required|string|max:50',
            'mail_host' => 'required|string|max:255',
            'mail_port' => 'required|integer|min:1|max:65535',
            'mail_username' => 'required|string|max:255',
            'mail_password' => 'required|string|max:255',
            'mail_encryption' => 'nullable|string|max:10',
            'mail_from_address' => 'required|email|max:255',
            'mail_from_name' => 'required|string|max:255',
            
            // Registration Settings
            'registration_enabled' => 'boolean',
            'registration_deadline' => 'nullable|date',
            'max_registrations_per_competition' => 'nullable|integer|min:1',
            
            // File Upload Settings
            'max_file_size' => 'required|integer|min:1|max:100', // MB
            'allowed_file_types' => 'required|string|max:255',
            
            // Notification Settings
            'email_notifications' => 'boolean',
            'sms_notifications' => 'boolean',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $settings = $request->except(['_token', '_method', 'app_logo', 'app_favicon']);

            // Handle logo upload
            if ($request->hasFile('app_logo')) {
                $logo = $request->file('app_logo');
                $logoPath = $logo->store('settings', 'public');
                $settings['app_logo'] = $logoPath;
                
                // Delete old logo
                $oldLogo = $this->getSetting('app_logo');
                if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                    Storage::disk('public')->delete($oldLogo);
                }
            }

            // Handle favicon upload
            if ($request->hasFile('app_favicon')) {
                $favicon = $request->file('app_favicon');
                $faviconPath = $favicon->store('settings', 'public');
                $settings['app_favicon'] = $faviconPath;
                
                // Delete old favicon
                $oldFavicon = $this->getSetting('app_favicon');
                if ($oldFavicon && Storage::disk('public')->exists($oldFavicon)) {
                    Storage::disk('public')->delete($oldFavicon);
                }
            }

            // Save settings to cache and file
            $this->saveSettings($settings);

            // Update environment variables for critical settings
            $this->updateEnvFile([
                'APP_NAME' => $settings['app_name'],
                'MAIL_MAILER' => $settings['mail_driver'],
                'MAIL_HOST' => $settings['mail_host'],
                'MAIL_PORT' => $settings['mail_port'],
                'MAIL_USERNAME' => $settings['mail_username'],
                'MAIL_PASSWORD' => $settings['mail_password'],
                'MAIL_ENCRYPTION' => $settings['mail_encryption'] ?? 'null',
                'MAIL_FROM_ADDRESS' => $settings['mail_from_address'],
                'MAIL_FROM_NAME' => '"' . $settings['mail_from_name'] . '"',
                'MIDTRANS_SERVER_KEY' => $settings['midtrans_server_key'],
                'MIDTRANS_CLIENT_KEY' => $settings['midtrans_client_key'],
                'MIDTRANS_IS_PRODUCTION' => $settings['midtrans_is_production'] ? 'true' : 'false',
            ]);

            return back()->with('success', 'Pengaturan berhasil disimpan.');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Gagal menyimpan pengaturan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Get all settings
     * 
     * @return array
     */
    private function getSettings()
    {
        return Cache::remember('app_settings', 3600, function () {
            $settingsFile = storage_path('app/settings.json');
            
            if (file_exists($settingsFile)) {
                return json_decode(file_get_contents($settingsFile), true);
            }

            // Default settings
            return [
                'app_name' => config('app.name', 'UNAS Fest 2025'),
                'app_description' => 'Platform kompetisi dan festival UNAS',
                'contact_email' => 'info@unasfest.ac.id',
                'contact_phone' => '+62 21 1234567',
                'contact_address' => 'Universitas Nasional, Jakarta',
                'midtrans_server_key' => config('midtrans.server_key'),
                'midtrans_client_key' => config('midtrans.client_key'),
                'midtrans_is_production' => config('midtrans.is_production', false),
                'mail_driver' => config('mail.default', 'smtp'),
                'mail_host' => config('mail.mailers.smtp.host'),
                'mail_port' => config('mail.mailers.smtp.port', 587),
                'mail_username' => config('mail.mailers.smtp.username'),
                'mail_password' => config('mail.mailers.smtp.password'),
                'mail_encryption' => config('mail.mailers.smtp.encryption'),
                'mail_from_address' => config('mail.from.address'),
                'mail_from_name' => config('mail.from.name'),
                'registration_enabled' => true,
                'max_file_size' => 10, // MB
                'allowed_file_types' => 'pdf,doc,docx,jpg,jpeg,png',
                'email_notifications' => true,
                'sms_notifications' => false,
            ];
        });
    }

    /**
     * Get single setting value
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    private function getSetting($key, $default = null)
    {
        $settings = $this->getSettings();
        return $settings[$key] ?? $default;
    }

    /**
     * Save settings to file and cache
     * 
     * @param array $settings
     */
    private function saveSettings($settings)
    {
        $settingsFile = storage_path('app/settings.json');
        
        // Merge with existing settings
        $existingSettings = $this->getSettings();
        $settings = array_merge($existingSettings, $settings);
        
        // Save to file
        file_put_contents($settingsFile, json_encode($settings, JSON_PRETTY_PRINT));
        
        // Update cache
        Cache::put('app_settings', $settings, 3600);
    }

    /**
     * Update .env file
     * 
     * @param array $data
     */
    private function updateEnvFile($data)
    {
        $envFile = base_path('.env');
        $envContent = file_get_contents($envFile);

        foreach ($data as $key => $value) {
            $pattern = "/^{$key}=.*/m";
            $replacement = "{$key}={$value}";
            
            if (preg_match($pattern, $envContent)) {
                $envContent = preg_replace($pattern, $replacement, $envContent);
            } else {
                $envContent .= "\n{$replacement}";
            }
        }

        file_put_contents($envFile, $envContent);
    }
}
