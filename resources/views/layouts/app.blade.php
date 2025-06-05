<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/x-icon">
    <title>{{ auth()->user()->store->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="{{ asset('js/vue.global.js') }}"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Khmer:wght@100..900&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: "Noto Sans Khmer", sans-serif;
            background-color: #f8f9fa;
        }
        .navbar {
            box-shadow: 0 2px 4px rgba(0,0,0,.08);
            background: linear-gradient(135deg, #4b6cb7 0%, #182848 100%);
        }
        .navbar-brand {
            font-weight: 600;
            font-size: 1.5rem;
            color: white !important;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,.08);
            margin-bottom: 1rem;
        }
        .card-header {
            background-color: white;
            border-bottom: 1px solid #eee;
            border-radius: 15px 15px 0 0 !important;
            padding: 1.25rem;
        }
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 1.5rem;
            padding: 1.5rem;
        }
        .product-card {
            background: white;
            border-radius: 12px;
            padding: 1rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            border: 1px solid #eee;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .product-card img {
            border-radius: 8px;
            margin-bottom: 1rem;
            width: 100%;
            height: 120px;
            object-fit: contain;
        }
        .product-card h6 {
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #2d3748;
        }
        .product-card p {
            color: #4299e1;
            font-weight: 600;
            font-size: 1.1rem;
        }
        .cart-section {
            height: calc(100vh - 280px);
            overflow-y: auto;
            padding: 1rem;
        }
        .cart-section::-webkit-scrollbar {
            width: 6px;
        }
        .cart-section::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        .cart-section::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 3px;
        }
        .btn-group .btn {
            border-radius: 8px !important;
            margin: 0 0.25rem;
            font-weight: 500;
        }
        .btn-primary {
            background: #4b6cb7;
            border-color: #4b6cb7;
        }
        .btn-outline-primary {
            border-color: #4b6cb7;
            color: #4b6cb7;
        }
        .btn-outline-primary:hover {
            background: #4b6cb7;
            border-color: #4b6cb7;
        }
        .form-control, .form-select {
            border-radius: 8px;
            padding: 0.75rem 1rem;
            border: 1px solid #e2e8f0;
        }
        .form-control:focus, .form-select:focus {
            border-color: #4b6cb7;
            box-shadow: 0 0 0 0.2rem rgba(75, 108, 183, 0.25);
        }
        .table {
            margin-bottom: 0;
        }
        .table th {
            font-weight: 600;
            color: #2d3748;
            border-bottom: 2px solid #edf2f7;
        }
        .table td {
            vertical-align: middle;
            color: #4a5568;
        }
        .badge {
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 500;
        }
        .btn-success {
            background: linear-gradient(135deg, #48c6ef 0%, #6f86d6 100%);
            border: none;
            padding: 1rem;
            font-weight: 600;
            letter-spacing: 0.5px;
        }
        .btn-success:hover {
            background: linear-gradient(135deg, #6f86d6 0%, #48c6ef 100%);
            transform: translateY(-1px);
        }
        .input-group-sm .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
            border-radius: 0.25rem;
        }
        .search-box {
            position: relative;
        }
        .search-box i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #a0aec0;
        }
        .search-box input {
            padding-left: 2.5rem;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark position-sticky top-0 z-3">
        <div class="container-fluid mx-4">
            <a class="navbar-brand" href="/">
                <i class="fas fa-shopping-cart me-2"></i>
                {{ auth()->user()->store->name }}
            </a>

            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-transparent nav-link text-white" type="button">
                    <i id="fullscreen-icon" onclick="toggleFullscreen()" style="font-size:23px;color:white" class="fa-solid fa-maximize"></i>
                    <i id="minimize-icon" onclick="backToNormal()" class="fa-solid fa-minimize" style="display:none;font-size:22px;color:white"></i>
                </button>
               
                <div class="dropdown">
                    <button class="btn btn-transparent nav-link text-white dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user"></i>
                        {{ auth()->user()->name }}
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="left: -15px">
                        <li><a class="dropdown-item" href="#">Profile</a></li>
                        <li>
                            <form action="{{ route('logout') }}" method="post">
                                @csrf
                                <button type="submit" class="dropdown-item">Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
                

        </div>
    </nav>

    <div class="container-fluid py-4" style="padding-bottom: 20px !important;height: calc(100dvh - 62px);">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
         function toggleFullscreen() {
            const fullscreenIcon = document.getElementById('fullscreen-icon');
            const minimizeIcon = document.getElementById('minimize-icon');
            fullscreenIcon.style.display = 'none';
            minimizeIcon.style.display = 'block';
            const element = document.documentElement;
            if (element.requestFullscreen) {
                element.requestFullscreen();
            } else if (element.webkitRequestFullscreen) {
                element.webkitRequestFullscreen();
            } else if (element.msRequestFullscreen) {
                element.msRequestFullscreen();
            }
        }

        function backToNormal() {
            const fullscreenIcon = document.getElementById('fullscreen-icon');
            const minimizeIcon = document.getElementById('minimize-icon');
            fullscreenIcon.style.display = 'block';
            minimizeIcon.style.display = 'none';
            const element = document.documentElement;
            if (document.exitFullscreen) {
                document.exitFullscreen();
            } else if (document.webkitExitFullscreen) {
                document.webkitExitFullscreen();
            } else if (document.msExitFullscreen) {
                document.msExitFullscreen();
            }
        }
    </script>
    @yield('scripts')
</body>
</html> 