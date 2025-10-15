<?php

namespace Tests\Feature\Admin;

use App\Models\Faq;
use App\Models\CompetitionTimeline;
use App\Models\ContactInformation;
use App\Models\Sponsor;
use App\Models\TermsAndCondition;
use App\Models\Competition;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ContentManagementTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Create roles
        \Spatie\Permission\Models\Role::create(['name' => 'admin']);

        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');
    }

    // FAQ Tests
    /** @test */
    public function admin_can_create_faq_with_multilingual_fields()
    {
        $data = [
            'question' => 'Test Question',
            'question_en' => 'Test Question EN',
            'question_id' => 'Pertanyaan Test',
            'answer' => 'Test Answer',
            'answer_en' => 'Test Answer EN',
            'answer_id' => 'Jawaban Test',
            'order' => 1,
            'is_active' => true,
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.faqs.store'), $data);

        $response->assertRedirect(route('admin.faqs.index'));
        $this->assertDatabaseHas('faqs', [
            'question_en' => 'Test Question EN',
            'question_id' => 'Pertanyaan Test',
        ]);
    }

    /** @test */
    public function admin_can_update_faq()
    {
        $faq = Faq::factory()->create();

        $data = [
            'question' => 'Updated Question',
            'answer' => 'Updated Answer',
            'order' => 2,
            'is_active' => true,
        ];

        $response = $this->actingAs($this->admin)->put(route('admin.faqs.update', $faq), $data);

        $response->assertRedirect(route('admin.faqs.index'));
        $this->assertDatabaseHas('faqs', ['id' => $faq->id, 'question' => 'Updated Question']);
    }

    /** @test */
    public function admin_can_delete_faq()
    {
        $faq = Faq::factory()->create();

        $response = $this->actingAs($this->admin)->delete(route('admin.faqs.destroy', $faq));

        $response->assertRedirect(route('admin.faqs.index'));
        $this->assertDatabaseMissing('faqs', ['id' => $faq->id]);
    }

    // Competition Timeline Tests
    /** @test */
    public function admin_can_create_competition_timeline_with_multilingual_fields()
    {
        $competition = Competition::factory()->create();

        $data = [
            'competition_id' => $competition->id,
            'month' => 'January',
            'day' => 15,
            'year' => 2025,
            'title' => 'Registration Opens',
            'title_en' => 'Registration Opens',
            'title_id' => 'Pendaftaran Dibuka',
            'order' => 1,
            'is_active' => true,
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.competition-timelines.store'), $data);

        $response->assertRedirect(route('admin.competition-timelines.index'));
        $this->assertDatabaseHas('competition_timelines', [
            'title_en' => 'Registration Opens',
            'title_id' => 'Pendaftaran Dibuka',
        ]);
    }

    /** @test */
    public function admin_can_update_competition_timeline()
    {
        $timeline = CompetitionTimeline::factory()->create();

        $data = [
            'competition_id' => $timeline->competition_id,
            'month' => 'February',
            'day' => 20,
            'year' => 2025,
            'title' => 'Updated Title',
            'order' => 2,
            'is_active' => true,
        ];

        $response = $this->actingAs($this->admin)->put(route('admin.competition-timelines.update', $timeline), $data);

        $response->assertRedirect(route('admin.competition-timelines.index'));
        $this->assertDatabaseHas('competition_timelines', ['id' => $timeline->id, 'title' => 'Updated Title']);
    }

    // Sponsor Tests
    /** @test */
    public function admin_can_create_sponsor_with_logo_upload()
    {
        Storage::fake('public');

        $data = [
            'name' => 'Test Sponsor',
            'logo' => UploadedFile::fake()->image('logo.png'),
            'website' => 'https://example.com',
            'type' => 'gold',
            'order' => 1,
            'is_active' => true,
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.sponsors.store'), $data);

        $response->assertRedirect(route('admin.sponsors.index'));
        $this->assertDatabaseHas('sponsors', ['name' => 'Test Sponsor', 'type' => 'gold']);
        
        $sponsor = Sponsor::where('name', 'Test Sponsor')->first();
        Storage::disk('public')->assertExists($sponsor->logo);
    }

    /** @test */
    public function admin_can_delete_sponsor_and_logo()
    {
        Storage::fake('public');

        $sponsor = Sponsor::factory()->create([
            'logo' => UploadedFile::fake()->image('logo.png')->store('sponsors', 'public'),
        ]);

        $logoPath = $sponsor->logo;

        $response = $this->actingAs($this->admin)->delete(route('admin.sponsors.destroy', $sponsor));

        $response->assertRedirect(route('admin.sponsors.index'));
        $this->assertDatabaseMissing('sponsors', ['id' => $sponsor->id]);
        Storage::disk('public')->assertMissing($logoPath);
    }

    // Terms and Conditions Tests
    /** @test */
    public function admin_can_create_terms_with_multilingual_fields()
    {
        $data = [
            'title' => 'General Terms',
            'title_en' => 'General Terms',
            'title_id' => 'Syarat Umum',
            'content' => 'Test content',
            'content_en' => 'Test content EN',
            'content_id' => 'Konten test',
            'type' => 'general',
            'order' => 1,
            'is_active' => true,
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.terms-and-conditions.store'), $data);

        $response->assertRedirect(route('admin.terms-and-conditions.index'));
        $this->assertDatabaseHas('terms_and_conditions', [
            'title_en' => 'General Terms',
            'title_id' => 'Syarat Umum',
        ]);
    }

    /** @test */
    public function admin_can_update_terms()
    {
        $term = TermsAndCondition::factory()->create();

        $data = [
            'title' => 'Updated Terms',
            'content' => 'Updated content',
            'type' => 'privacy',
            'order' => 2,
            'is_active' => true,
        ];

        $response = $this->actingAs($this->admin)->put(route('admin.terms-and-conditions.update', $term), $data);

        $response->assertRedirect(route('admin.terms-and-conditions.index'));
        $this->assertDatabaseHas('terms_and_conditions', ['id' => $term->id, 'title' => 'Updated Terms']);
    }

    // Contact Information Tests
    /** @test */
    public function admin_can_update_contact_information()
    {
        $contact = ContactInformation::factory()->create();

        $data = [
            'email' => 'updated@example.com',
            'whatsapp' => '+628123456789',
            'instagram' => '@updated',
            'tiktok' => '@updated',
            'youtube' => '@updated',
            'address' => 'Updated Address',
            'is_active' => true,
        ];

        $response = $this->actingAs($this->admin)->put(route('admin.contact-information.update'), $data);

        $response->assertRedirect(route('admin.contact-information.index'));
        $this->assertDatabaseHas('contact_information', ['email' => 'updated@example.com']);
    }

    // Authorization Tests
    /** @test */
    public function guest_cannot_access_content_management_pages()
    {
        $this->get(route('admin.faqs.index'))->assertRedirect(route('login'));
        $this->get(route('admin.sponsors.index'))->assertRedirect(route('login'));
        $this->get(route('admin.terms-and-conditions.index'))->assertRedirect(route('login'));
    }
}

