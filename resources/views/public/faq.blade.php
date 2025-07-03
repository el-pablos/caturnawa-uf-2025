@extends('layouts.simple')

@php
    $seoPage = 'faq';
@endphp

@section('title', 'FAQ - UNAS Fest 2025')

@section('content')
<div class="container my-5">
    <!-- Hero Section -->
    <div class="row">
        <div class="col-12">
            <div class="jumbotron bg-primary text-white p-5 rounded mb-5">
                <div class="text-center">
                    <h1 class="display-4 fw-bold mb-3">
                        <i class="bi bi-question-circle me-3"></i>FAQ
                    </h1>
                    <p class="lead mb-0">
                        Temukan jawaban untuk pertanyaan yang sering diajukan seputar UNAS Fest 2025
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- FAQ Section -->
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow">
                <div class="card-header bg-secondary text-white text-center">
                    <h2 class="card-title mb-0">Pertanyaan Yang Sering Diajukan</h2>
                    <p class="mb-0">Berikut adalah kumpulan pertanyaan dan jawaban yang paling sering ditanyakan oleh peserta</p>
                </div>
                <div class="card-body">
                    <div class="accordion" id="faqAccordion">
                        @foreach($faqs as $index => $faq)
                        <div class="accordion-item mb-3">
                            <h2 class="accordion-header" id="heading{{ $index }}">
                                <button class="accordion-button {{ $index === 0 ? '' : 'collapsed' }}" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}"
                                        aria-expanded="{{ $index === 0 ? 'true' : 'false' }}" aria-controls="collapse{{ $index }}">
                                    <i class="bi bi-question-circle me-3 text-primary"></i>
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
    </div>

    <!-- Contact Section -->
    <div class="row mt-5">
        <div class="col-12">
            <h2 class="text-center mb-4">
                <i class="bi bi-headset text-warning"></i> 
                Masih Ada Pertanyaan?
            </h2>
        </div>
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-warning text-white text-center">
                    <h2 class="card-title mb-0">Masih Ada Pertanyaan?</h2>
                    <p class="mb-0">Jika Anda tidak menemukan jawaban yang dicari, jangan ragu untuk menghubungi tim kami</p>
                </div>
                <div class="card-body text-center">
                    <div class="row justify-content-center">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('public.contact') }}" class="btn btn-primary btn-lg w-100">
                                <i class="bi bi-envelope me-2"></i>Hubungi Kami
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="https://wa.me/6285817378442" class="btn btn-success btn-lg w-100" target="_blank">
                                <i class="bi bi-whatsapp me-2"></i>WhatsApp
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection