@extends('layouts.error-animated')

@section('title', '429 - Terlalu Banyak Permintaan')

@section('error-title', 'Too Many Requests')
@section('error-code', '429')
@section('error-description', 'Anda telah mengirim terlalu banyak permintaan dalam waktu singkat.')
@section('error-message', 'Silakan tunggu sebentar sebelum mencoba lagi.')

@section('error-actions')
    <a href="javascript:history.back()" class="btn-error">
        ← Kembali
    </a>
    <a href="{{ route('public.home') }}" class="btn-error">
        Beranda
    </a>
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
