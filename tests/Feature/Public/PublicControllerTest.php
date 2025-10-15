<?php

namespace Tests\Feature\Public;

use App\Models\Competition;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test home page loads successfully
     */
    public function test_home_page_loads_successfully()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewIs('public.home-simple');
        $response->assertViewHas('competitions');
        $response->assertViewHas('leaderboard');
    }

    /**
     * Test home page alternative route
     */
    public function test_home_page_alternative_route_loads()
    {
        $response = $this->get('/home');

        $response->assertStatus(200);
        $response->assertViewIs('public.home-simple');
    }

    /**
     * Test competitions listing page loads
     */
    public function test_competitions_listing_page_loads()
    {
        $response = $this->get('/competitions');

        $response->assertStatus(200);
        $response->assertViewIs('public.competitions-simple');
        $response->assertViewHas('competitions');
        $response->assertViewHas('stats');
        $response->assertViewHas('categories');
    }

    /**
     * Test competitions page with category filter
     */
    public function test_competitions_page_filters_by_category()
    {
        Competition::factory()->create([
            'category' => 'event_debate',
            'is_active' => true,
        ]);

        Competition::factory()->create([
            'category' => 'event_dcc',
            'is_active' => true,
        ]);

        $response = $this->get('/competitions?category=event_debate');

        $response->assertStatus(200);
        $response->assertViewHas('competitions', function ($competitions) {
            return $competitions->every(fn($comp) => $comp->category === 'event_debate');
        });
    }

    /**
     * Test competitions page with status filter - open
     */
    public function test_competitions_page_filters_by_status_open()
    {
        Competition::factory()->create([
            'is_active' => true,
            'registration_start' => now()->subDays(5),
            'registration_end' => now()->addDays(5),
        ]);

        Competition::factory()->create([
            'is_active' => true,
            'registration_start' => now()->addDays(10),
            'registration_end' => now()->addDays(20),
        ]);

        $response = $this->get('/competitions?status=open');

        $response->assertStatus(200);
        $response->assertViewHas('competitions', function ($competitions) {
            return $competitions->count() === 1;
        });
    }

    /**
     * Test competitions page with status filter - upcoming
     */
    public function test_competitions_page_filters_by_status_upcoming()
    {
        Competition::factory()->create([
            'is_active' => true,
            'registration_start' => now()->addDays(10),
            'registration_end' => now()->addDays(20),
        ]);

        $response = $this->get('/competitions?status=upcoming');

        $response->assertStatus(200);
        $response->assertViewHas('competitions', function ($competitions) {
            return $competitions->count() === 1;
        });
    }

    /**
     * Test competitions page with status filter - closed
     */
    public function test_competitions_page_filters_by_status_closed()
    {
        Competition::factory()->create([
            'is_active' => true,
            'registration_start' => now()->subDays(30),
            'registration_end' => now()->subDays(5),
        ]);

        $response = $this->get('/competitions?status=closed');

        $response->assertStatus(200);
        $response->assertViewHas('competitions', function ($competitions) {
            return $competitions->count() === 1;
        });
    }

    /**
     * Test competitions page with search query
     */
    public function test_competitions_page_searches_by_keyword()
    {
        Competition::factory()->create([
            'name' => 'Debate Competition 2025',
            'is_active' => true,
        ]);

        Competition::factory()->create([
            'name' => 'Digital Content Competition',
            'is_active' => true,
        ]);

        $response = $this->get('/competitions?search=Debate');

        $response->assertStatus(200);
        $response->assertViewHas('competitions', function ($competitions) {
            return $competitions->count() === 1 && 
                   str_contains($competitions->first()->name, 'Debate');
        });
    }

    /**
     * Test competition detail page loads
     */
    public function test_competition_detail_page_loads()
    {
        $competition = Competition::factory()->create([
            'slug' => 'test-competition',
            'is_active' => true,
        ]);

        $response = $this->get("/competition/{$competition->slug}");

        $response->assertStatus(200);
        $response->assertViewIs('public.competition');
        $response->assertViewHas('competition', function ($comp) use ($competition) {
            return $comp->id === $competition->id;
        });
        $response->assertViewHas('descriptions');
    }

    /**
     * Test competition detail page returns 404 for non-existent competition
     */
    public function test_competition_detail_returns_404_for_non_existent()
    {
        $response = $this->get('/competition/non-existent-slug');

        $response->assertStatus(404);
    }

    /**
     * Test about page loads
     */
    public function test_about_page_loads()
    {
        $response = $this->get('/about');

        $response->assertStatus(200);
        $response->assertViewIs('public.about');
        $response->assertViewHas('departments');
    }

    /**
     * Test team page loads (redirects to about)
     */
    public function test_team_page_loads()
    {
        $response = $this->get('/team');

        $response->assertStatus(200);
        $response->assertViewIs('public.about');
    }

    /**
     * Test testimonials page loads
     */
    public function test_testimonials_page_loads()
    {
        $response = $this->get('/testimonials');

        $response->assertStatus(200);
        $response->assertViewIs('public.testimonials');
        $response->assertViewHas('testimonials');
    }

    /**
     * Test testimonial submission with valid data
     */
    public function test_testimonial_submission_with_valid_data()
    {
        $data = [
            'name' => 'John Doe',
            'institution' => 'Test University',
            'competition' => 'Teknologi',
            'year' => 2024,
            'rating' => 5,
            'comment' => 'Great competition!',
        ];

        $response = $this->post('/testimonials', $data);

        $response->assertRedirect();
        $response->assertSessionHas('success');
    }

    /**
     * Test testimonial submission with invalid data
     */
    public function test_testimonial_submission_with_invalid_data()
    {
        $data = [
            'name' => '',
            'institution' => '',
            'rating' => 6, // Invalid rating
        ];

        $response = $this->post('/testimonials', $data);

        $response->assertSessionHasErrors(['name', 'institution', 'competition', 'year', 'rating', 'comment']);
    }

    /**
     * Test contact page loads
     */
    public function test_contact_page_loads()
    {
        $response = $this->get('/contact');

        $response->assertStatus(200);
        $response->assertViewIs('public.contact');
    }

    /**
     * Test contact form submission with valid data
     */
    public function test_contact_form_submission_with_valid_data()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'subject' => 'Test Subject',
            'message' => 'This is a test message.',
        ];

        $response = $this->post('/contact', $data);

        $response->assertRedirect();
        $response->assertSessionHas('success');
    }

    /**
     * Test contact form submission with invalid email
     */
    public function test_contact_form_submission_with_invalid_email()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'invalid-email',
            'subject' => 'Test Subject',
            'message' => 'This is a test message.',
        ];

        $response = $this->post('/contact', $data);

        $response->assertSessionHasErrors(['email']);
    }

    /**
     * Test contact form submission with missing required fields
     */
    public function test_contact_form_submission_with_missing_fields()
    {
        $data = [];

        $response = $this->post('/contact', $data);

        $response->assertSessionHasErrors(['name', 'email', 'subject', 'message']);
    }

    /**
     * Test blog listing page loads
     */
    public function test_blog_listing_page_loads()
    {
        $response = $this->get('/blog');

        $response->assertStatus(200);
        $response->assertViewIs('public.blog');
        $response->assertViewHas('posts');
    }

    /**
     * Test blog detail page loads
     */
    public function test_blog_detail_page_loads()
    {
        $response = $this->get('/blog/test-slug');

        $response->assertStatus(200);
        $response->assertViewIs('public.blog-detail');
        $response->assertViewHas('post');
    }

    /**
     * Test timeline page loads
     */
    public function test_timeline_page_loads()
    {
        $response = $this->get('/timeline');

        $response->assertStatus(200);
        $response->assertViewIs('public.timeline');
        $response->assertViewHas('timeline');
    }

    /**
     * Test FAQ page loads
     */
    public function test_faq_page_loads()
    {
        $response = $this->get('/faq');

        $response->assertStatus(200);
        $response->assertViewIs('public.faq');
        $response->assertViewHas('faqs');
    }

    /**
     * Test privacy policy page loads
     */
    public function test_privacy_policy_page_loads()
    {
        $response = $this->get('/privacy');

        $response->assertStatus(200);
        $response->assertViewIs('public.privacy');
    }

    /**
     * Test terms of service page loads
     */
    public function test_terms_of_service_page_loads()
    {
        $response = $this->get('/terms');

        $response->assertStatus(200);
        $response->assertViewIs('public.terms');
    }

    /**
     * Test home page displays active competitions only
     */
    public function test_home_page_displays_active_competitions_only()
    {
        Competition::factory()->create(['is_active' => true]);
        Competition::factory()->create(['is_active' => false]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewHas('competitions', function ($competitions) {
            return $competitions->every(fn($comp) => $comp->is_active === true);
        });
    }

    /**
     * Test competitions page pagination works
     */
    public function test_competitions_page_pagination_works()
    {
        Competition::factory()->count(15)->create(['is_active' => true]);

        $response = $this->get('/competitions');

        $response->assertStatus(200);
        $response->assertViewHas('competitions', function ($competitions) {
            return $competitions instanceof \Illuminate\Pagination\LengthAwarePaginator;
        });
    }

    /**
     * Test competitions page shows statistics
     */
    public function test_competitions_page_shows_statistics()
    {
        Competition::factory()->count(5)->create(['is_active' => true]);

        $response = $this->get('/competitions');

        $response->assertStatus(200);
        $response->assertViewHas('stats', function ($stats) {
            return isset($stats['total_competitions']) &&
                   isset($stats['open_registrations']) &&
                   isset($stats['upcoming_competitions']);
        });
    }

    /**
     * Test testimonial submission validates rating range
     */
    public function test_testimonial_submission_validates_rating_range()
    {
        $data = [
            'name' => 'John Doe',
            'institution' => 'Test University',
            'competition' => 'Teknologi',
            'year' => 2024,
            'rating' => 0, // Invalid - below minimum
            'comment' => 'Test comment',
        ];

        $response = $this->post('/testimonials', $data);

        $response->assertSessionHasErrors(['rating']);
    }

    /**
     * Test contact form validates message length
     */
    public function test_contact_form_validates_message_length()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'subject' => 'Test Subject',
            'message' => str_repeat('a', 1001), // Exceeds max length
        ];

        $response = $this->post('/contact', $data);

        $response->assertSessionHasErrors(['message']);
    }
}
