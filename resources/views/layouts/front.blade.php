<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <!-- SEO Meta Tags -->
  <title>@yield('title', config('app.name', 'My Website'))</title>
  <meta name="description" content="@yield('meta_description', '')">
  <meta name="keywords" content="@yield('meta_keywords', '')">
  <meta name="author" content="@yield('meta_author', config('app.name'))">

  <!-- Favicons -->
  <link rel="icon" href="{{ asset('assets/img/favicon.png') }}">
  <link rel="apple-touch-icon" href="{{ asset('assets/img/apple-touch-icon.png') }}">

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- Vendor CSS -->
  <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendor/aos/aos.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendor/glightbox/css/glightbox.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendor/fontawesome-free/css/all.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendor/swiper/swiper-bundle.min.css') }}">

  <!-- Main CSS -->
  <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">

  <!-- PawPet Theme Overrides -->
  <style>
    :root {
      --default-font: 'Quicksand', sans-serif;
      --heading-font: 'Quicksand', sans-serif;
      --nav-font: 'Quicksand', sans-serif;

      --background-color: #ffffff;
      --accent-color: #f59e0b; /* Orange accent */
      --nav-hover-color: #f59e0b;
      --nav-dropdown-hover-color: #f59e0b;
    }
    
    .light-background {
      --background-color: #fffaf5 !important; /* Cream sangat ringan */
      --surface-color: #ffffff;
    }
  </style>

  @stack('styles')
</head>

<body class="@yield('body-class', '')">

  {{-- Preloader --}}
  <div id="preloader"></div>

  {{-- Header --}}
  @include('partials.header')

  {{-- Main Content --}}
  <main class="main">
    @yield('content')
  </main>

  {{-- Footer --}}
  @include('partials.footer')

  {{-- Scroll Top --}}
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center">
    <i class="bi bi-arrow-up-short"></i>
  </a>

  {{-- Vendor JS --}}
  <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/php-email-form/validate.js') }}"></script>
  <script src="{{ asset('assets/vendor/aos/aos.js') }}"></script>
  <script src="{{ asset('assets/vendor/glightbox/js/glightbox.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/purecounter/purecounter_vanilla.js') }}"></script>
  <script src="{{ asset('assets/vendor/swiper/swiper-bundle.min.js') }}"></script>

  {{-- Main JS --}}
  <script src="{{ asset('assets/js/main.js') }}"></script>

  @stack('scripts')

</body>
</html>