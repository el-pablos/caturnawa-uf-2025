@extends('layouts.app')

@section('title', '404 - Page Not Found')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="text-center">
                <div class="error mx-auto" data-text="404">404</div>
                <p class="lead text-gray-800 mb-5">Page Not Found</p>
                <p class="text-gray-500 mb-0">The page you are looking for doesn't exist</p>
                <a href="{{ route('dashboard') }}">&larr; Back to Dashboard</a>
            </div>
        </div>
    </div>
</div>

<style>
.error {
    font-size: 7rem;
    position: relative;
    line-height: 1;
    width: 12.5rem;
}

.error:before {
    content: attr(data-text);
    position: absolute;
    text-shadow: 0px 0px 2px blue;
    color: transparent;
    background: repeating-linear-gradient(45deg, transparent, transparent 2px, rgba(255,255,255,0.5) 2px, rgba(255,255,255,0.5) 4px);
    -webkit-background-clip: text;
}
</style>
@endsection
