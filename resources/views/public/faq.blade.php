@extends('layouts.public')

@section('content')
<!-- Hero Section -->
<section class="hero-section py-5" style="min-height: 60vh;">
    <div class="container">
        <div class="row align-items-center justify-content-center text-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold text-white mb-4 font-poppins">
                    Frequently Asked Questions
                </h1>
                <p class="lead text-white-50 mb-4">
                    Temukan jawaban untuk pertanyaan yang sering diajukan seputar UNAS Fest 2025
                </p>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="accordion" id="faqAccordion">
                    @foreach($faqs as $index => $faq)
                    <div class="accordion-item border-0 shadow-sm mb-3" data-aos="fade-up" data-aos-delay="{{ ($index + 1) * 100 }}">
                        <h2 class="accordion-header" id="heading{{ $index }}">
                            <button class="accordion-button {{ $index === 0 ? '' : 'collapsed' }}" type="button" 
                                    data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}" 
                                    aria-expanded="{{ $index === 0 ? 'true' : 'false' }}" aria-controls="collapse{{ $index }}">
                                <i class="bi bi-question-circle me-2 text-primary"></i>
                                {{ $faq['question'] }}
                            </button>
                        </h2>
                        <div id="collapse{{ $index }}" class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}" 
                             aria-labelledby="heading{{ $index }}" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <p class="text-muted mb-0">{{ $faq['answer'] }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section class="section bg-light">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <h2 class="section-title font-poppins">Masih Ada Pertanyaan?</h2>
                <p class="section-subtitle">
                    Jika Anda tidak menemukan jawaban yang dicari, jangan ragu untuk menghubungi tim kami
                </p>
                <div class="d-flex flex-wrap gap-3 justify-content-center">
                    <a href="{{ route('public.contact') }}" class="btn btn-primary btn-lg">
                        <i class="bi bi-envelope me-2"></i>Hubungi Kami
                    </a>
                    <a href="https://wa.me/6281234567890" class="btn btn-success btn-lg" target="_blank">
                        <i class="bi bi-whatsapp me-2"></i>WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
