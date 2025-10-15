<?php

namespace Tests\Feature;

use App\Models\Faq;
use App\Models\TermsAndCondition;
use App\Models\CompetitionTimeline;
use App\Models\Competition;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class MultilingualTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function language_switcher_changes_locale()
    {
        $response = $this->get(route('language.switch', 'en'));

        $response->assertRedirect();
        $this->assertEquals('en', Session::get('locale'));
    }

    /** @test */
    public function language_switcher_validates_locale()
    {
        $response = $this->get(route('language.switch', 'invalid'));

        $response->assertStatus(400);
    }

    /** @test */
    public function faq_displays_localized_content_in_english()
    {
        app()->setLocale('en');

        $faq = Faq::factory()->create([
            'question' => 'Default Question',
            'question_en' => 'English Question',
            'question_id' => 'Indonesian Question',
            'answer' => 'Default Answer',
            'answer_en' => 'English Answer',
            'answer_id' => 'Indonesian Answer',
            'is_active' => true,
        ]);

        $this->assertEquals('English Question', $faq->localized_question);
        $this->assertEquals('English Answer', $faq->localized_answer);
    }

    /** @test */
    public function faq_displays_localized_content_in_indonesian()
    {
        app()->setLocale('id');

        $faq = Faq::factory()->create([
            'question' => 'Default Question',
            'question_en' => 'English Question',
            'question_id' => 'Indonesian Question',
            'answer' => 'Default Answer',
            'answer_en' => 'English Answer',
            'answer_id' => 'Indonesian Answer',
            'is_active' => true,
        ]);

        $this->assertEquals('Indonesian Question', $faq->localized_question);
        $this->assertEquals('Indonesian Answer', $faq->localized_answer);
    }

    /** @test */
    public function faq_falls_back_to_default_when_translation_missing()
    {
        app()->setLocale('en');

        $faq = Faq::factory()->create([
            'question' => 'Default Question',
            'question_en' => null,
            'answer' => 'Default Answer',
            'answer_en' => null,
            'is_active' => true,
        ]);

        $this->assertEquals('Default Question', $faq->localized_question);
        $this->assertEquals('Default Answer', $faq->localized_answer);
    }

    /** @test */
    public function terms_displays_localized_content()
    {
        app()->setLocale('en');

        $term = TermsAndCondition::factory()->create([
            'title' => 'Default Title',
            'title_en' => 'English Title',
            'title_id' => 'Indonesian Title',
            'content' => 'Default Content',
            'content_en' => 'English Content',
            'content_id' => 'Indonesian Content',
            'is_active' => true,
        ]);

        $this->assertEquals('English Title', $term->localized_title);
        $this->assertEquals('English Content', $term->localized_content);
    }

    /** @test */
    public function timeline_displays_localized_content()
    {
        app()->setLocale('en');

        $competition = Competition::factory()->create();
        $timeline = CompetitionTimeline::factory()->create([
            'competition_id' => $competition->id,
            'title' => 'Default Title',
            'title_en' => 'English Title',
            'title_id' => 'Indonesian Title',
            'is_active' => true,
        ]);

        $this->assertEquals('English Title', $timeline->localized_title);
    }

    /** @test */
    public function public_faq_page_uses_localized_content()
    {
        Session::put('locale', 'en');

        Faq::factory()->create([
            'question' => 'Default Question',
            'question_en' => 'English Question',
            'answer' => 'Default Answer',
            'answer_en' => 'English Answer',
            'is_active' => true,
        ]);

        $response = $this->get(route('public.faq'));

        $response->assertStatus(200);
        $response->assertSee('English Question');
        $response->assertSee('English Answer');
    }

    /** @test */
    public function public_terms_page_uses_localized_content()
    {
        Session::put('locale', 'en');

        TermsAndCondition::factory()->create([
            'title' => 'Default Title',
            'title_en' => 'English Title',
            'content' => 'Default Content',
            'content_en' => 'English Content',
            'is_active' => true,
        ]);

        $response = $this->get(route('public.terms'));

        $response->assertStatus(200);
        $response->assertSee('English Title');
        $response->assertSee('English Content');
    }

    /** @test */
    public function set_locale_middleware_sets_app_locale_from_session()
    {
        Session::put('locale', 'en');

        $response = $this->get(route('public.home'));

        $this->assertEquals('en', app()->getLocale());
    }

    /** @test */
    public function set_locale_middleware_defaults_to_indonesian()
    {
        Session::forget('locale');

        $response = $this->get(route('public.home'));

        $this->assertEquals('id', app()->getLocale());
    }
}

