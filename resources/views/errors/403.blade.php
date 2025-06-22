@extends('layouts.app')

@section('title', '403 - Forbidden')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="text-center">
                <div class="error mx-auto" data-text="403">403</div>
                <p class="lead text-gray-800 mb-5">Access Forbidden</p>
                <p class="text-gray-500 mb-0">You don't have permission to access this resource</p>
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
    text-shadow: 0px 0px 2px red;
    color: transparent;
    background: repeating-linear-gradient(45deg, transparent, transparent 2px, rgba(255,255,255,0.5) 2px, rgba(255,255,255,0.5) 4px);
    -webkit-background-clip: text;
}
</style>
@endsection
