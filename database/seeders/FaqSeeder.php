<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing FAQs
        Faq::truncate();

        // FAQ data from UNASFEST-2025
        $faqs = [
            [
                'question' => 'How do I register for the competition of UNAS FEST?',
                'answer' => 'Visit the caturnawa.unasfest.com, choose one of the Competition \'Kompetisi Debat Bahasa Indonesia\', click \'Register Now\', fill the form, review, and make the payment',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'question' => 'How do I register for the competition of UNAS FEST?',
                'answer' => 'Click the posters at the top of the homepage to register for the competition or click the \'Register\' button on the activity page',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'question' => 'How much is the registration fee for the UNAS FEST competition?',
                'answer' => 'The registration fees vary across competitions, starting from Rp 300,000 to Rp 550,000',
                'order' => 3,
                'is_active' => true,
            ],
            [
                'question' => 'What is the deadline for registration for the UNAS FEST competition?',
                'answer' => 'The deadline for registration for the Kompetisi Debat Bahasa Indonesia (KDBI) is August 30, 2025',
                'order' => 4,
                'is_active' => true,
            ],
            [
                'question' => 'When will the winners be announced?',
                'answer' => 'The winners will be announced on October 17 2025, after the final round sessions',
                'order' => 5,
                'is_active' => true,
            ],
            [
                'question' => 'Where can I see the winner announcement?',
                'answer' => 'The winner announcement will be available on this caturnawa.unasfest.com',
                'order' => 6,
                'is_active' => true,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::create($faq);
        }

        $this->command->info('✅ FAQ seeder completed: 6 FAQs created');
    }
}

