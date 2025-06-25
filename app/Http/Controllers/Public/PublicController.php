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
     * Display the home page
     */
    public function home()
    {
        $this->seoService->setPage('home');
        
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

        return view('public.home', compact('competitions', 'stats'));
    }

    /**
     * Display competitions page
     */
    public function competitions()
    {
        $this->seoService->setPage('competitions');
        
        $competitions = Competition::active()
            ->with(['registrations' => function($query) {
                $query->where('status', 'confirmed');
            }])
            ->get()
            ->groupBy('category');

        return view('public.competitions', compact('competitions'));
    }

    /**
     * Display about page
     */
    public function about()
    {
        $this->seoService->setPage('about');
        
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
}
