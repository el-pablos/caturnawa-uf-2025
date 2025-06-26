<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\Registration;
use App\Models\User;
use App\Services\SEOService;
use App\Services\ModernSEOService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

/**
 * Controller for public pages with SEO optimization
 */
class PublicController extends Controller
{
    protected $seoService;
    protected $modernSeoService;

    public function __construct(SEOService $seoService, ModernSEOService $modernSeoService)
    {
        $this->seoService = $seoService;
        $this->modernSeoService = $modernSeoService;
    }

    /**
     * Display the home page
     */
    public function home()
    {
        // Set modern SEO
        $this->modernSeoService->setHomePage();
        $this->modernSeoService->addOrganizationSchema();

        // Get active competitions
        $competitions = Competition::active()
            ->where('registration_start', '<=', now())
            ->where('registration_end', '>=', now())
            ->take(3)
            ->get();

        // Get statistics
        $stats = [
            'participants' => Registration::where('status', 'confirmed')->count(),
            'competitions' => Competition::active()->count(),
            'universities' => Registration::distinct('institution')->count(),
            'total_prize' => 500000000, // 500 million
        ];

        return view('public.modern-home', compact('competitions', 'stats'));
    }

    /**
     * Display competitions page
     */
    public function competitions()
    {
        // Set modern SEO
        $this->modernSeoService->setCompetitionsPage();

        $competitions = Competition::active()
            ->with(['registrations' => function($query) {
                $query->where('status', 'confirmed');
            }])
            ->get()
            ->groupBy('category');

        // Get statistics
        $stats = [
            'participants' => Registration::where('status', 'confirmed')->count(),
            'competitions' => Competition::active()->count(),
            'universities' => Registration::distinct('institution')->count(),
            'total_prize' => 500000000, // 500 million
        ];

        return view('public.competitions', compact('competitions', 'stats'));
    }

    /**
     * Display competition detail page
     */
    public function competitionDetail($slug)
    {
        $competition = Competition::where('slug', $slug)->firstOrFail();

        $this->seoService->setPage('competitions')
            ->setCustomData([
                'title' => $competition->name . ' - UNAS Fest 2025',
                'description' => $competition->description,
            ]);

        return view('public.competition-detail', compact('competition'));
    }

    /**
     * Display team page
     */
    public function team()
    {
        $this->seoService->setPage('about');

        // Same as about page but focused on team
        return $this->about();
    }

    /**
     * Display about page
     */
    public function about()
    {
        // Set modern SEO
        $this->modernSeoService->setAboutPage();
        
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
        $this->seoService->setPage('testimonials');
        
        // Sample testimonials (in real app, this would come from database)
        $testimonials = [
            [
                'name' => 'Ahmad Rizki',
                'institution' => 'Universitas Indonesia',
                'competition' => 'Teknologi',
                'rating' => 5,
                'comment' => 'UNAS Fest 2025 memberikan pengalaman luar biasa! Kompetisinya sangat menantang dan berkualitas tinggi.',
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

            return back()->with('success', 'Testimoni Anda berhasil dikirim! Terima kasih atas feedback yang diberikan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat menyimpan testimoni. Silakan coba lagi.');
        }
    }

    /**
     * Display contact page
     */
    public function contact()
    {
        $this->seoService->setPage('contact');
        
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
            
            return back()->with('success', 'Pesan Anda berhasil dikirim! Kami akan segera menghubungi Anda.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat mengirim pesan. Silakan coba lagi.');
        }
    }

    /**
     * Display blog page
     */
    public function blog()
    {
        $this->seoService->setPage('blog');
        
        // Sample blog posts (in real app, this would come from database)
        $posts = [
            [
                'title' => 'Tips Sukses Mengikuti Kompetisi Teknologi',
                'slug' => 'tips-sukses-kompetisi-teknologi',
                'excerpt' => 'Panduan lengkap untuk mempersiapkan diri mengikuti kompetisi teknologi di UNAS Fest 2025.',
                'content' => '',
                'featured_image' => asset('assets/images/blog/tech-tips.jpg'),
                'published_at' => now()->subDays(5),
                'author' => 'Tim UNAS Fest',
                'category' => 'Tips',
                'tags' => ['teknologi', 'kompetisi', 'tips']
            ],
            [
                'title' => 'Panduan Pendaftaran UNAS Fest 2025',
                'slug' => 'panduan-pendaftaran-unas-fest-2025',
                'excerpt' => 'Langkah-langkah mudah untuk mendaftar kompetisi UNAS Fest 2025.',
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

        $this->seoService->setPage('blog')
            ->setCustomData([
                'title' => $post['title'] . ' - UNAS Fest 2025 Blog',
                'description' => 'Baca artikel lengkap: ' . $post['title'],
            ]);

        return view('public.blog-detail', compact('post'));
    }

    /**
     * Display FAQ page
     */
    public function faq()
    {
        $this->seoService->setCustomData([
            'title' => 'FAQ - Frequently Asked Questions | UNAS Fest 2025',
            'description' => 'Temukan jawaban untuk pertanyaan yang sering diajukan seputar UNAS Fest 2025.',
        ]);

        $faqs = [
            [
                'question' => 'Bagaimana cara mendaftar kompetisi?',
                'answer' => 'Anda dapat mendaftar melalui halaman kompetisi dengan mengklik tombol "Daftar Sekarang" pada kategori yang diminati.'
            ],
            [
                'question' => 'Apakah ada biaya pendaftaran?',
                'answer' => 'Biaya pendaftaran bervariasi tergantung kategori kompetisi. Informasi lengkap dapat dilihat di halaman detail kompetisi.'
            ],
            // Add more FAQs as needed
        ];

        return view('public.faq', compact('faqs'));
    }

    /**
     * Display privacy policy page
     */
    public function privacy()
    {
        $this->seoService->setCustomData([
            'title' => 'Privacy Policy | UNAS Fest 2025',
            'description' => 'Kebijakan privasi UNAS Fest 2025 mengenai pengumpulan, penggunaan, dan perlindungan data pribadi.',
        ]);

        return view('public.privacy');
    }

    /**
     * Display terms of service page
     */
    public function terms()
    {
        $this->seoService->setCustomData([
            'title' => 'Terms of Service | UNAS Fest 2025',
            'description' => 'Syarat dan ketentuan penggunaan layanan UNAS Fest 2025.',
        ]);

        return view('public.terms');
    }
}
