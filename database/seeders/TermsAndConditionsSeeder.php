<?php

namespace Database\Seeders;

use App\Models\TermsAndCondition;
use Illuminate\Database\Seeder;

class TermsAndConditionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing terms and conditions
        TermsAndCondition::truncate();

        // Terms and conditions data
        $termsAndConditions = [
            [
                'title' => 'General Terms and Conditions',
                'content' => 'Welcome to Caturnawa UNAS FEST 2025. By accessing and using this website, you accept and agree to be bound by the terms and provision of this agreement. If you do not agree to abide by the above, please do not use this service.

1. Use of Service
   - You must be at least 17 years old to register for competitions
   - You must provide accurate and complete information during registration
   - You are responsible for maintaining the confidentiality of your account

2. User Conduct
   - You agree not to use the service for any unlawful purpose
   - You agree not to interfere with or disrupt the service
   - You agree to comply with all applicable laws and regulations

3. Intellectual Property
   - All content on this website is the property of UNAS FEST 2025
   - You may not reproduce, distribute, or create derivative works without permission

4. Limitation of Liability
   - UNAS FEST 2025 shall not be liable for any indirect, incidental, or consequential damages
   - We reserve the right to modify or discontinue the service at any time

5. Changes to Terms
   - We reserve the right to update these terms at any time
   - Continued use of the service constitutes acceptance of modified terms',
                'type' => TermsAndCondition::TYPE_GENERAL,
                'order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Competition Rules and Regulations',
                'content' => 'Competition Terms and Conditions for Caturnawa UNAS FEST 2025

1. Eligibility
   - Participants must be active students from recognized educational institutions
   - Valid student ID card (KTM) is required for verification
   - Team composition must comply with competition-specific requirements

2. Registration
   - Registration is done online through caturnawa.unasfest.com
   - Registration fee must be paid within 24 hours of registration
   - Late payments will result in automatic cancellation

3. Competition Rules
   - All participants must attend the technical meeting
   - Participants must follow the competition schedule strictly
   - Any form of cheating or plagiarism will result in immediate disqualification

4. Submission Requirements
   - All submissions must be original work
   - Submissions must meet the specified format and deadline
   - Late submissions will not be accepted

5. Judging Criteria
   - Judging will be conducted by certified judges
   - Judges\' decisions are final and binding
   - Scoring criteria will be announced during technical meeting

6. Prizes and Awards
   - Winners will be announced on the specified date
   - Prizes will be distributed during the award ceremony
   - Tax obligations are the responsibility of winners

7. Code of Conduct
   - Participants must maintain professional behavior
   - Respect for judges, organizers, and fellow participants is mandatory
   - Violation of code of conduct may result in disqualification',
                'type' => TermsAndCondition::TYPE_COMPETITION,
                'order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Privacy Policy',
                'content' => 'Privacy Policy for Caturnawa UNAS FEST 2025

1. Information We Collect
   - Personal information (name, email, phone number, address)
   - Educational information (institution, student ID, faculty)
   - Payment information (transaction details, payment proof)
   - Competition data (submissions, scores, feedback)

2. How We Use Your Information
   - To process your registration and participation
   - To communicate important updates and announcements
   - To verify your eligibility and identity
   - To improve our services and user experience

3. Information Sharing
   - We do not sell or rent your personal information to third parties
   - We may share information with judges for evaluation purposes
   - We may share information with sponsors for prize distribution
   - We may disclose information if required by law

4. Data Security
   - We implement appropriate security measures to protect your data
   - We use secure payment gateways for financial transactions
   - We regularly update our security protocols

5. Your Rights
   - You have the right to access your personal information
   - You have the right to request correction of inaccurate data
   - You have the right to request deletion of your data (subject to legal requirements)

6. Cookies and Tracking
   - We use cookies to enhance user experience
   - You can disable cookies in your browser settings
   - Some features may not function properly without cookies

7. Changes to Privacy Policy
   - We may update this privacy policy from time to time
   - We will notify users of significant changes
   - Continued use constitutes acceptance of updated policy

8. Contact Information
   - For privacy-related inquiries, contact: privacy@unasfest.com',
                'type' => TermsAndCondition::TYPE_PRIVACY,
                'order' => 3,
                'is_active' => true,
            ],
            [
                'title' => 'Payment Terms and Conditions',
                'content' => 'Payment Terms and Conditions for Caturnawa UNAS FEST 2025

1. Registration Fees
   - Registration fees vary by competition and registration phase
   - Early Bird: Discounted rate for early registrations
   - Phase 1: Standard rate for mid-period registrations
   - Phase 2: Higher rate for late registrations

2. Payment Methods
   - Bank transfer to designated account
   - E-wallet payments (GoPay, OVO, Dana)
   - Virtual account payments
   - Credit/debit card payments (via Midtrans)

3. Payment Process
   - Complete registration form on the website
   - Receive payment instructions via email
   - Make payment within 24 hours
   - Upload payment proof for verification
   - Wait for admin confirmation (max 2x24 hours)

4. Payment Confirmation
   - Payment will be verified by admin within 2x24 hours
   - Confirmation will be sent via email and WhatsApp
   - Registration is only valid after payment confirmation

5. Refund Policy
   - Registration fees are non-refundable after payment confirmation
   - Refunds may be granted in case of event cancellation by organizers
   - Refund requests must be submitted in writing
   - Refund processing takes 14-30 business days

6. Late Payment
   - Payments not received within 24 hours will result in automatic cancellation
   - You may re-register, but the registration fee may change based on current phase

7. Payment Issues
   - For payment-related issues, contact: payment@unasfest.com
   - Include transaction details and payment proof
   - Response time: 1x24 hours during business days

8. Price Changes
   - We reserve the right to change registration fees
   - Price changes will be announced on the website
   - Registered participants are not affected by price changes

9. Tax and Additional Fees
   - All prices are inclusive of applicable taxes
   - Payment gateway fees may apply depending on payment method
   - Winners are responsible for tax obligations on prizes',
                'type' => TermsAndCondition::TYPE_PAYMENT,
                'order' => 4,
                'is_active' => true,
            ],
        ];

        foreach ($termsAndConditions as $term) {
            TermsAndCondition::create($term);
        }

        $this->command->info('✅ Terms and Conditions seeder completed: 4 T&C records created');
    }
}

