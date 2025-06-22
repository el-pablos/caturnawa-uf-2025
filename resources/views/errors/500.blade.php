@extends('layouts.app')

@section('title', '500 - Server Error')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="text-center">
                <div class="error mx-auto" data-text="500">500</div>
                <p class="lead text-gray-800 mb-5">Internal Server Error</p>
                <p class="text-gray-500 mb-0">Something went wrong on our end</p>
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
    text-shadow: 0px 0px 2px orange;
    color: transparent;
    background: repeating-linear-gradient(45deg, transparent, transparent 2px, rgba(255,255,255,0.5) 2px, rgba(255,255,255,0.5) 4px);
    -webkit-background-clip: text;
}
</style>
@endsection
