<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website Dalam Pemeliharaan - UNAS Fest 2025</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
        }
        
        .maintenance-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            padding: 3rem;
            text-align: center;
            max-width: 600px;
            width: 100%;
            animation: fadeInUp 0.8s ease-out;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .maintenance-icon {
            font-size: 5rem;
            color: #667eea;
            margin-bottom: 1.5rem;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
            100% {
                transform: scale(1);
            }
        }
        
        .maintenance-title {
            color: #2d3748;
            font-weight: 700;
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }
        
        .maintenance-message {
            color: #4a5568;
            font-size: 1.1rem;
            line-height: 1.6;
            margin-bottom: 2rem;
        }
        
        .maintenance-details {
            background: rgba(102, 126, 234, 0.1);
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .maintenance-details h5 {
            color: #667eea;
            font-weight: 600;
            margin-bottom: 1rem;
        }
        
        .maintenance-details p {
            color: #4a5568;
            margin-bottom: 0.5rem;
        }
        
        .contact-info {
            background: rgba(255, 255, 255, 0.8);
            border-radius: 15px;
            padding: 1.5rem;
            border: 1px solid rgba(102, 126, 234, 0.2);
        }
        
        .contact-info h6 {
            color: #2d3748;
            font-weight: 600;
            margin-bottom: 1rem;
        }
        
        .contact-link {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .contact-link:hover {
            color: #764ba2;
            text-decoration: underline;
        }
        
        .loading-dots {
            display: inline-block;
            margin-left: 5px;
        }
        
        .loading-dots span {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: #667eea;
            margin: 0 2px;
            animation: loading 1.4s infinite ease-in-out both;
        }
        
        .loading-dots span:nth-child(1) { animation-delay: -0.32s; }
        .loading-dots span:nth-child(2) { animation-delay: -0.16s; }
        
        @keyframes loading {
            0%, 80%, 100% {
                transform: scale(0);
            }
            40% {
                transform: scale(1);
            }
        }
        
        @media (max-width: 768px) {
            .maintenance-container {
                padding: 2rem;
                margin: 1rem;
            }
            
            .maintenance-title {
                font-size: 2rem;
            }
            
            .maintenance-icon {
                font-size: 4rem;
            }
        }
    </style>
</head>
<body>
    <div class="maintenance-container">
        <div class="maintenance-icon">
            <i class="bi bi-gear-fill"></i>
        </div>
        
        <h1 class="maintenance-title">Website Dalam Pemeliharaan</h1>
        
        <p class="maintenance-message">
            {{ $message ?? 'Maaf yahh website dalam masa pemeliharaan, silahkan coba nanti' }}
            <span class="loading-dots">
                <span></span>
                <span></span>
                <span></span>
            </span>
        </p>
        
        <div class="maintenance-details">
            <h5><i class="bi bi-info-circle me-2"></i>Informasi Pemeliharaan</h5>
            <p><i class="bi bi-clock me-2"></i>Kami sedang melakukan pemeliharaan sistem untuk meningkatkan performa</p>
            <p><i class="bi bi-shield-check me-2"></i>Data Anda tetap aman selama proses pemeliharaan</p>
            <p><i class="bi bi-arrow-clockwise me-2"></i>Website akan kembali normal setelah pemeliharaan selesai</p>
        </div>
        
        <div class="contact-info">
            <h6><i class="bi bi-envelope me-2"></i>Butuh Bantuan?</h6>
            <p>Jika ada pertanyaan mendesak, silahkan hubungi:</p>
            <p>
                <i class="bi bi-envelope-fill me-2"></i>
                <a href="mailto:info@unasfest.com" class="contact-link">info@unasfest.com</a>
            </p>
            <p>
                <i class="bi bi-instagram me-2"></i>
                <a href="https://instagram.com/unasfest" class="contact-link" target="_blank">@unasfest</a>
            </p>
        </div>
        
        <div class="mt-4">
            <small class="text-muted">
                <i class="bi bi-heart-fill text-danger me-1"></i>
                UNAS Fest 2025 - Caturnawa
            </small>
        </div>
    </div>
    
    <!-- Auto refresh every 30 seconds -->
    <script>
        setTimeout(function() {
            location.reload();
        }, 30000);
    </script>
</body>
</html>
