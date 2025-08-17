<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\Registration;
use App\Models\User;
use App\Services\SEOService;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

/**
 * Controller for public pages with SEO optimization
 */
class PublicController extends Controller
{
    protected $seoService;

    public function __construct(SEOService $seoService)
    {
        $this->seoService = $seoService;
    }

    /**
     * Display the home page with leaderboard
     */
    public function home()
    {
        // Get all competitions (active and upcoming)
        $competitions = Competition::active()
            ->orderBy('registration_start', 'asc')
            ->get();

        // Get leaderboard data for home page (top 10)
        $leaderboard = $this->getHomeLeaderboard();

        return view('public.home-simple', compact('competitions', 'leaderboard'));
    }

    /**
     * Get leaderboard data for home page (by competition)
     */
    private function getHomeLeaderboard()
    {
        $competitions = Competition::active()->get();
        $leaderboardByCompetition = [];

        foreach ($competitions as $competition) {
            // Get leaderboard entries for this competition (top 4)
            $entries = \App\Models\LeaderboardEntry::active()
                ->byCompetition($competition->id)
                ->topRanks(4)
                ->get();

            if ($entries->count() > 0) {
                $leaderboard = $entries->map(function ($entry) {
                    return [
                        'team_name' => $entry->team_name,
                        'participant_name' => $entry->participant_name,
                        'competition' => $entry->competition->name,
                        'institution' => $entry->institution,
                        'score' => $entry->score,
                        'victory_points' => $entry->victory_points,
                        'rank' => $entry->rank,
                        'rank_type' => $entry->rank_type,
                    ];
                });

                $leaderboardByCompetition[] = [
                    'competition' => $competition,
                    'leaderboard' => $leaderboard
                ];
            }
        }

        return $leaderboardByCompetition;
    }

    /**
     * Display competitions page
     */
    public function competitions(Request $request)
    {
        // Get filter parameters
        $category = $request->get('category');
        $status = $request->get('status');
        $search = $request->get('search');

        // Start with active competitions query
        $query = Competition::active()
            ->with(['registrations' => function($query) {
                $query->where('status', 'confirmed');
            }]);

        // Apply category filter
        if ($category && $category !== 'all') {
            $query->where('category', $category);
        }

        // Apply status filter
        if ($status && $status !== 'all') {
            $now = now();
            switch ($status) {
                case 'open':
                    $query->where('registration_start', '<=', $now)
                          ->where('registration_end', '>=', $now);
                    break;
                case 'upcoming':
                    $query->where('registration_start', '>', $now);
                    break;
                case 'closed':
                    $query->where('registration_end', '<', $now);
                    break;
            }
        }

        // Apply search filter
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('name_en', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('description_en', 'like', "%{$search}%");
            });
        }

        $competitions = $query->orderBy('registration_start', 'asc')->paginate(12);

        // Get all categories for filter dropdown
        $categories = [
            'all' => 'All Categories',
            'event_debate' => 'Debate Competition',
            'event_dcc' => 'Digital Content Competition',
            'event_scientific_paper' => 'Scientific Paper Competition'
        ];

        // Get statistics for the stats section (from all competitions)
        $allCompetitions = Competition::active()->get();
        $stats = [
            'total_competitions' => $allCompetitions->count(),
            'open_registrations' => $allCompetitions->filter(function($comp) {
                $now = now();
                return $comp->registration_start <= $now && $comp->registration_end >= $now;
            })->count(),
            'upcoming_competitions' => $allCompetitions->filter(function($comp) {
                return $comp->registration_start > now();
            })->count(),
        ];

        return view('public.competitions-simple', compact('competitions', 'stats', 'categories', 'category', 'status', 'search'));
    }

    /**
     * Display competition detail page
     */
    public function competitionDetail($slug)
    {
        $competition = Competition::where('slug', $slug)->firstOrFail();
        
        // Load competition descriptions for display
        $descriptions = $competition->descriptions()
            ->where('is_active', true)
            ->orderBy('order')
            ->orderBy('created_at')
            ->get();

        return view('public.competition', compact('competition', 'descriptions'));
    }

    /**
     * Display team page
     */
    public function team()
    {
        // Same as about page but focused on team
        return $this->about();
    }

    /**
     * Display about page
     */
    public function about()
    {
        
        // Department structure
        $departments = [
            'security' => [
                'name' => 'Department of Security',
                'name_id' => 'Departemen Keamanan',
                'description' => 'Bertanggung jawab atas keamanan dan ketertiban selama acara berlangsung',
                'icon' => 'shield-check',
                'color' => 'primary',
                'members' => []
            ],
            'infrastructure' => [
                'name' => 'Department of Infrastructure',
                'name_id' => 'Departemen Infrastruktur',
                'description' => 'Mengelola infrastruktur dan fasilitas yang dibutuhkan untuk acara',
                'icon' => 'building',
                'color' => 'secondary',
                'members' => []
            ],
            'fnb' => [
                'name' => 'Department of Food & Beverage',
                'name_id' => 'Departemen Makanan & Minuman',
                'description' => 'Mengatur penyediaan makanan dan minuman untuk peserta dan panitia',
                'icon' => 'cup-hot',
                'color' => 'success',
                'members' => []
            ],
            'health' => [
                'name' => 'Department of Health',
                'name_id' => 'Departemen Kesehatan',
                'description' => 'Memastikan kesehatan dan keselamatan seluruh peserta acara',
                'icon' => 'heart-pulse',
                'color' => 'danger',
                'members' => []
            ],
            'debate' => [
                'name' => 'Department of Debate Competition',
                'name_id' => 'Departemen Kompetisi Debat',
                'description' => 'Mengelola kompetisi debat dan kegiatan terkait',
                'icon' => 'chat-dots',
                'color' => 'warning',
                'members' => []
            ],
            'pr' => [
                'name' => 'Department of Public Relations',
                'name_id' => 'Departemen Hubungan Masyarakat',
                'description' => 'Mengelola komunikasi dan hubungan dengan media serta masyarakat',
                'icon' => 'megaphone',
                'color' => 'info',
                'members' => []
            ],
            'finance' => [
                'name' => 'Department of Finance',
                'name_id' => 'Departemen Keuangan',
                'description' => 'Mengelola keuangan dan anggaran acara',
                'icon' => 'currency-dollar',
                'color' => 'dark',
                'members' => []
            ],
            'scientific' => [
                'name' => 'Department of Scientific Paper Competition',
                'name_id' => 'Departemen Kompetisi Karya Tulis Ilmiah',
                'description' => 'Mengelola kompetisi karya tulis ilmiah',
                'icon' => 'journal-text',
                'color' => 'primary',
                'members' => []
            ],
            'digital' => [
                'name' => 'Department of Digital Content Competition',
                'name_id' => 'Departemen Kompetisi Konten Digital',
                'description' => 'Mengelola kompetisi konten digital dan multimedia',
                'icon' => 'camera-video',
                'color' => 'secondary',
                'members' => []
            ],
            'partnership' => [
                'name' => 'Department of Partnership',
                'name_id' => 'Departemen Kemitraan',
                'description' => 'Mengelola kerjasama dan sponsorship',
                'icon' => 'handshake',
                'color' => 'success',
                'members' => []
            ],
            'entertainment' => [
                'name' => 'Department of Entertainment',
                'name_id' => 'Departemen Hiburan',
                'description' => 'Mengatur acara hiburan dan kegiatan rekreasi',
                'icon' => 'music-note',
                'color' => 'danger',
                'members' => []
            ],
            'secretarial' => [
                'name' => 'Department of Secretarial Affairs',
                'name_id' => 'Departemen Urusan Kesekretariatan',
                'description' => 'Mengelola administrasi dan dokumentasi acara',
                'icon' => 'file-text',
                'color' => 'warning',
                'members' => []
            ],
            'advertising' => [
                'name' => 'Department of Advertising',
                'name_id' => 'Departemen Periklanan',
                'description' => 'Mengelola promosi dan materi iklan',
                'icon' => 'bullhorn',
                'color' => 'info',
                'members' => []
            ],
            'it' => [
                'name' => 'Department of IT',
                'name_id' => 'Departemen IT',
                'description' => 'Mengelola sistem teknologi informasi dan website',
                'icon' => 'laptop',
                'color' => 'dark',
                'members' => []
            ],
        ];

        return view('public.about', compact('departments'));
    }

    /**
     * Display testimonials page
     */
    public function testimonials()
    {
        
        // Sample testimonials (in real app, this would come from database)
        $testimonials = [
            [
                'name' => 'Ahmad Rizki',
                'institution' => 'Universitas Indonesia',
                'competition' => 'Teknologi',
                'rating' => 5,
                'comment' => 'Caturnawa UNAS FEST 2025 memberikan pengalaman luar biasa! Kompetisinya sangat menantang dan berkualitas tinggi.',
                'avatar' => asset('assets/images/testimonials/default-avatar.png'),
                'year' => 2024
            ],
            [
                'name' => 'Sari Dewi',
                'institution' => 'Institut Teknologi Bandung',
                'competition' => 'Kesehatan',
                'rating' => 5,
                'comment' => 'Panitia sangat profesional dan acara diselenggarakan dengan sangat baik. Recommended!',
                'avatar' => asset('assets/images/testimonials/default-avatar.png'),
                'year' => 2024
            ],
            [
                'name' => 'Budi Santoso',
                'institution' => 'Universitas Gadjah Mada',
                'competition' => 'Biodiversitas',
                'rating' => 5,
                'comment' => 'Kompetisi yang sangat inspiratif dan memberikan wawasan baru tentang lingkungan.',
                'avatar' => asset('assets/images/testimonials/default-avatar.png'),
                'year' => 2024
            ],
        ];

        return view('public.testimonials', compact('testimonials'));
    }

    /**
     * Store testimonial
     */
    public function storeTestimonial(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'institution' => 'required|string|max:255',
            'competition' => 'required|string|in:Teknologi,Kesehatan,Biodiversitas',
            'year' => 'required|integer|min:2020|max:' . date('Y'),
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'name.required' => 'Nama wajib diisi',
            'institution.required' => 'Institusi wajib diisi',
            'competition.required' => 'Kompetisi wajib dipilih',
            'year.required' => 'Tahun wajib dipilih',
            'rating.required' => 'Rating wajib dipilih',
            'comment.required' => 'Testimoni wajib diisi',
            'photo.image' => 'File harus berupa gambar',
            'photo.max' => 'Ukuran file maksimal 2MB',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // In real app, save to database
            // For now, just return success message

            return back()->with('success', 'Your testimonial has been sent successfully! Thank you for your feedback.');
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while saving your testimonial. Please try again.');
        }
    }

    /**
     * Display contact page
     */
    public function contact()
    {

        return view('public.contact');
    }

    /**
     * Handle contact form submission
     */
    public function sendContact(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
        ], [
            'name.required' => 'Nama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'subject.required' => 'Subjek wajib diisi',
            'message.required' => 'Pesan wajib diisi',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Send email notification (implement based on your mail configuration)
            // Mail::to(config('seo.contact.email'))->send(new ContactFormMail($request->all()));
            
            return back()->with('success', 'Your message has been sent successfully! We will contact you soon.');
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while sending your message. Please try again.');
        }
    }

    /**
     * Display blog page
     */
    public function blog()
    {
        
        // Sample blog posts (in real app, this would come from database)
        $posts = [
            [
                'title' => 'Tips Sukses Mengikuti Kompetisi Teknologi',
                'slug' => 'tips-sukses-kompetisi-teknologi',
                'excerpt' => 'Panduan lengkap untuk mempersiapkan diri mengikuti kompetisi teknologi di Caturnawa UNAS FEST 2025.',
                'content' => '',
                'featured_image' => asset('assets/images/blog/tech-tips.jpg'),
                'published_at' => now()->subDays(5),
                'author' => 'Tim UNAS Fest',
                'category' => 'Tips',
                'tags' => ['teknologi', 'kompetisi', 'tips']
            ],
            [
                'title' => 'Panduan Pendaftaran Caturnawa UNAS FEST 2025',
                'slug' => 'panduan-pendaftaran-unas-fest-2025',
                'excerpt' => 'Langkah-langkah mudah untuk mendaftar kompetisi UNAS FEST 2025.',
                'content' => '',
                'featured_image' => asset('assets/images/blog/registration-guide.jpg'),
                'published_at' => now()->subDays(10),
                'author' => 'Tim UNAS Fest',
                'category' => 'Panduan',
                'tags' => ['pendaftaran', 'panduan', 'unas fest']
            ],
        ];

        return view('public.blog', compact('posts'));
    }

    /**
     * Display blog detail page
     */
    public function blogDetail($slug)
    {
        // Sample blog post (in real app, this would come from database)
        $post = [
            'title' => 'Tips Sukses Mengikuti Kompetisi Teknologi',
            'slug' => $slug,
            'content' => 'Content of the blog post...',
            'featured_image' => asset('assets/images/blog/tech-tips.jpg'),
            'published_at' => now()->subDays(5),
            'author' => 'Tim UNAS Fest',
            'category' => 'Tips',
            'tags' => ['teknologi', 'kompetisi', 'tips']
        ];



        return view('public.blog-detail', compact('post'));
    }

    /**
     * Display FAQ page
     */
    public function faq()
    {
        $this->seoService->setCustomData([
            'title' => 'FAQ - Frequently Asked Questions | Caturnawa UNAS FEST 2025',
            'description' => 'Find answers to frequently asked questions about the Caturnawa UNAS FEST 2025.',
        ]);

        $faqs = [
            [
                'question' => 'How do I register for the competition?',
                'answer' => 'You can register through the competition page by clicking the "Register Now" button in the desired category. Make sure you have created an account and are logged in.'
            ],
            [
                'question' => 'Is there a registration fee?',
                'answer' => 'The registration fee varies depending on the competition category. Complete information can be found on the competition detail page. Payment can be made through various available methods.'
            ],
            [
                'question' => 'Who can participate in the Caturnawa UNAS FEST 2025?',
                'answer' => 'The Caturnawa UNAS FEST 2025 is open to all active students at universities/higher education institutions in Indonesia. Participants must show proof of active student status upon registration.'
            ],
            [
                'question' => 'What is the total prize money available?',
                'answer' => 'The total prize for the Caturnawa UNAS FEST 2025 reaches 500 million rupiah, distributed across various competition categories: Technology, Health, and Biodiversity.'
            ],
            [
                'question' => 'Can I register for more than one competition?',
                'answer' => 'Yes, participants are allowed to register in multiple competition categories at once. However, make sure you can participate in all registered competitions properly.'
            ],
            [
                'question' => 'What is the format of the competition?',
                'answer' => 'The competition is held in a hybrid format (online and offline). The initial stage is an online file selection, followed by presentations and judging at the UNAS Jakarta campus.'
            ],
            [
                'question' => 'When is the registration deadline?',
                'answer' => 'The registration deadline varies for each competition category. Please check the competition detail page for the exact dates. We recommend registering early.'
            ],
            [
                'question' => 'Is accommodation provided for participants from outside Jakarta?',
                'answer' => 'The committee will help find information on nearby affordable accommodation. For more information, please contact our team via WhatsApp or email.'
            ],
            [
                'question' => 'How can I check my registration status?',
                'answer' => 'After logging into your account, go to the participant dashboard to see the status of all your competition registrations. You will also receive email notifications for any status changes.'
            ],
            [
                'question' => 'What should I prepare for the competition?',
                'answer' => 'Preparation varies for each category. In general: a proposal/scientific paper, presentation materials, and other supporting documents. Full details are available in the guidebook which will be sent after registration is confirmed.'
            ],
            [
                'question' => 'Will participants receive a certificate?',
                'answer' => 'Yes, all participants who complete the competition will receive a certificate of participation. Winners will receive a special certificate and a trophy.'
            ],
            [
                'question' => 'What if I want to cancel my registration?',
                'answer' => 'Registration can be canceled through the participant dashboard or by contacting our team. The terms and conditions for a refund can be found on the terms of service page.'
            ]
        ];

        return view('public.faq', compact('faqs'));
    }

    /**
     * Display privacy policy page
     */
    public function privacy()
    {
        $this->seoService->setCustomData([
            'title' => 'Privacy Policy | Caturnawa UNAS FEST 2025',
            'description' => 'Kebijakan privasi Caturnawa UNAS FEST 2025 mengenai pengumpulan, penggunaan, dan perlindungan data pribadi.',
        ]);

        return view('public.privacy');
    }

    /**
     * Display terms of service page
     */
    public function terms()
    {
        $this->seoService->setCustomData([
            'title' => 'Terms of Service | Caturnawa UNAS FEST 2025',
            'description' => 'Syarat dan ketentuan penggunaan layanan Caturnawa UNAS FEST 2025.',
        ]);

        return view('public.terms');
    }

    /**
     * Display timeline page
     */
    public function timeline()
    {
        $this->seoService->setCustomData([
            'title' => 'Timeline | Caturnawa UNAS FEST 2025',
            'description' => 'Timeline lengkap kegiatan Caturnawa UNAS FEST 2025 dari pendaftaran hingga pengumuman pemenang.',
        ]);

        // Timeline data
        $timeline = [
            [
                'date' => '1 Januari - 28 Februari 2025',
                'title' => 'Pendaftaran Dibuka',
                'description' => 'Periode pendaftaran untuk semua kategori kompetisi UNAS FEST 2025.',
                'status' => 'active',
                'icon' => 'bi-calendar-plus'
            ],
            [
                'date' => '1 - 15 Maret 2025',
                'title' => 'Pengumpulan Karya',
                'description' => 'Peserta mengumpulkan karya sesuai dengan ketentuan masing-masing kompetisi.',
                'status' => 'upcoming',
                'icon' => 'bi-upload'
            ],
            [
                'date' => '16 - 31 Maret 2025',
                'title' => 'Penilaian Juri',
                'description' => 'Tim juri melakukan penilaian terhadap semua karya yang masuk.',
                'status' => 'upcoming',
                'icon' => 'bi-star'
            ],
            [
                'date' => '5 April 2025',
                'title' => 'Pengumuman Finalis',
                'description' => 'Pengumuman finalis untuk setiap kategori kompetisi.',
                'status' => 'upcoming',
                'icon' => 'bi-megaphone'
            ],
            [
                'date' => '12 April 2025',
                'title' => 'Final & Awarding',
                'description' => 'Acara final dan pengumuman pemenang Caturnawa UNAS FEST 2025.',
                'status' => 'upcoming',
                'icon' => 'bi-trophy'
            ]
        ];

        return view('public.timeline', compact('timeline'));
    }
}
