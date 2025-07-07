@extends('layouts.error')

@section('title', '429 - Terlalu Banyak Permintaan')

@section('content')
<div class="error-header">
    <div class="error-icon">
        <i class="bi bi-speedometer"></i>
    </div>
    <h1 class="error-code">429</h1>
    <h2 class="error-title">Terlalu Banyak Permintaan</h2>
</div>

<div class="error-body">
    <p class="error-description">
        Anda telah mengirim terlalu banyak permintaan dalam waktu singkat. Silakan tunggu sebentar sebelum mencoba lagi.
    </p>
    
    <div class="error-actions">
        <a href="javascript:history.back()" class="btn-error-primary">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
        
        <a href="{{ route('public.home') }}" class="btn-error-secondary">
            <i class="bi bi-house me-2"></i>Beranda
        </a>
    </div>
</div>

<div class="error-footer">
    <small>
        <i class="bi bi-info-circle me-1"></i>
        Pembatasan ini diterapkan untuk menjaga performa sistem. Silakan coba lagi dalam beberapa menit.
    </small>
</div>
@endsection

@section('scripts')
<script>
    // Countdown timer
    let countdown = 60;
    const countdownElement = document.createElement('div');
    countdownElement.className = 'mt-3';
    countdownElement.innerHTML = `<small class="text-muted">Halaman akan dimuat ulang dalam <span id="countdown">${countdown}</span> detik</small>`;
    document.querySelector('.error-actions').appendChild(countdownElement);
    
    const timer = setInterval(function() {
        countdown--;
        document.getElementById('countdown').textContent = countdown;
        
        if (countdown <= 0) {
            clearInterval(timer);
            window.location.reload();
        }
    }, 1000);
</script>
@endsection
