<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>500 - Server Error - {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="{{ asset('css/unas-theme.css') }}" rel="stylesheet">
</head>
<body>
<div class="container-fluid vh-100 d-flex align-items-center justify-content-center" style="background: #f8fafc;">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow-lg border-0">
                <div class="card-body text-center p-5">
                    <div class="error-icon mb-4">
                        <i class="bi bi-exclamation-octagon text-danger" style="font-size: 5rem;"></i>
                    </div>
                    <h1 class="display-4 fw-bold text-danger mb-3">500</h1>
                    <h3 class="mb-3">Internal Server Error</h3>
                    <p class="text-muted mb-4">Something went wrong on our end</p>
                    <div class="d-flex gap-3 justify-content-center">
                        <a href="{{ route('dashboard') }}" class="btn btn-primary">
                            <i class="bi bi-arrow-left me-2"></i>Back to Dashboard
                        </a>
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-house me-2"></i>Go Home
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


