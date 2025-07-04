<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'maintenance_mode',
                'value' => '0',
                'type' => 'boolean',
                'description' => 'Enable/disable maintenance mode'
            ],
            [
                'key' => 'maintenance_message',
                'value' => 'Maaf yahh website dalam masa pemeliharaan, silahkan coba nanti',
                'type' => 'string',
                'description' => 'Message to show when maintenance mode is enabled'
            ],
            [
                'key' => 'registration_open',
                'value' => '1',
                'type' => 'boolean',
                'description' => 'Enable/disable user registration'
            ],
            [
                'key' => 'registration_closed_message',
                'value' => 'Pendaftaran sedang ditutup, silahkan tungu periode selanjutnya yaaaa....',
                'type' => 'string',
                'description' => 'Message to show when registration is closed'
            ],
            [
                'key' => 'site_name',
                'value' => 'Caturnawa - UNAS Fest 2025',
                'type' => 'string',
                'description' => 'Website name'
            ],
            [
                'key' => 'site_description',
                'value' => 'Platform kompetisi UNAS Fest 2025',
                'type' => 'string',
                'description' => 'Website description'
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
