<?php

namespace Database\Seeders;

use App\Models\ContactInformation;
use Illuminate\Database\Seeder;

class ContactInformationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing contact information
        ContactInformation::truncate();

        // Create official contact information
        ContactInformation::create([
            'email' => 'contact@unasfest.com',
            'whatsapp' => '+62 812-3456-7890',
            'instagram' => '@unasfest',
            'tiktok' => '@unasfest',
            'youtube' => 'UNAS FEST Official',
            'address' => 'Universitas Nasional, Jl. Sawo Manila No.61, Pejaten, Pasar Minggu, Jakarta Selatan 12520',
            'is_active' => true,
        ]);

        $this->command->info('✅ Contact Information seeder completed: 1 record created');
    }
}

