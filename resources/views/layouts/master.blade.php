<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Yến Sào Hoàng Đăng</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        :root {
            /* Increased to cover two-row header (top + nav) */
            --header-height: 140px;
        }
        body {
            padding-top: var(--header-height);
        }

        /* Branding colors */
        .brand-brown { background-color: #1e3d37; }
        .brand-light { background-color: #FFF8DC; }
        .brand-accent { background-color: #FFF4C1; }
        .text-brown { color: #1e3d37; }

        /* Header animations */
        #mainHeader {
            transition: box-shadow 0.3s ease, transform 0.3s ease;
        }
        #mainHeader.shadow-header {
            box-shadow: 0 2px 15px rgba(0,0,0,0.15);
        }
        #mainHeader.header-hidden {
            transform: translateY(-100%);
        }

        /* Nav link styles */
        .nav-link {
            color: rgba(255,255,255,0.9) !important;
            transition: color 0.3s ease, background-color 0.3s ease;
            border-radius: 4px;
        }
        .nav-link:hover {
            color: #fff !important;
            background-color: rgba(255,255,255,0.06);
        }

        /* Hover effects */
        .hover-warning:hover {
            background-color: #F5B041 !important;
            color: #000 !important;
        }

        /* Dropdown styling */
        .dropdown-item:hover {
            background-color: #FFF4C1;
        }
        .dropdown-item:active {
            background-color: #8B4513;
            color: white;
        }

        /* Cart preview */
        .cart-preview-menu {
            margin-top: 0.5rem;
        }
        @media (max-width: 991.98px) {
            .cart-preview-menu {
                position: fixed !important;
                top: var(--bs-dropdown-spacer) !important;
                right: 1rem !important;
                left: 1rem !important;
                width: auto !important;
                margin-top: 0;
            }
        }

        /* Badge animation */
        .animate-badge {
            animation: badge-pop 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            transform-origin: center center;
        }
        @keyframes badge-pop {
            0% { transform: translate(-50%, -50%) scale(0); }
            100% { transform: translate(-50%, -50%) scale(1); }
        }

        /* Responsive improvements */
        @media (max-width: 991.98px) {
            .navbar-nav .dropdown-menu {
                background-color: rgba(255,248,220,0.95);
                backdrop-filter: blur(10px);
                margin-top: 0;
            }
            .navbar-collapse {
                background-color: rgba(30,61,55,0.95);
                backdrop-filter: blur(10px);
                padding: 1rem;
                border-radius: 0.5rem;
                margin-top: 0.5rem;
            }
        }
    </style>
</head>
<body>
    @include('partials.header')

    {{-- Flash messages --}}
    @if(session('success') || session('error') || $errors->any())
        <div class="container mt-3">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    @endif

    <main class="py-4">
        @yield('content')
    </main>

    @include('partials.footer')

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Header scroll effect
        let lastScroll = 0;
        const header = document.getElementById('mainHeader');
        
        window.addEventListener('scroll', () => {
            const currentScroll = window.pageYOffset;
            
            // Add/remove shadow
            if (currentScroll > 0) {
                header.classList.add('shadow-header');
            } else {
                header.classList.remove('shadow-header');
            }
            
            // Auto-hide on scroll down, show on scroll up
            if (currentScroll > lastScroll && currentScroll > 200) {
                header.classList.add('header-hidden');
            } else {
                header.classList.remove('header-hidden');
            }
            
            lastScroll = currentScroll;
        });
    </script>
</body>
</html>
