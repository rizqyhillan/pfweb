@extends('layouts.front')

@section('title', 'Home')

@section('body-class', 'index-page')

@section('content')<main class="main">

    <!-- Hero Section -->
    <section id="hero" class="hero section">

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row align-items-center">
          <div class="col-lg-6">
            <div class="hero-content">
              <div class="trust-badges mb-4" data-aos="fade-right" data-aos-delay="200">
                <div class="badge-item">
                  <i class="bi bi-shield-check"></i>
                  <span>Accredited</span>
                </div>
                <div class="badge-item">
                  <i class="bi bi-clock"></i>
                  <span>24/7 Emergency</span>
                </div>
                <div class="badge-item">
                  <i class="bi bi-star-fill"></i>
                  <span>4.9/5 Rating</span>
                </div>
              </div>

              <h1 data-aos="fade-right" data-aos-delay="300">
                Teman Setia, <span class="highlight">Perawatan Terbaik</span> untuk Anabul
              </h1>

              <p class="hero-description" data-aos="fade-right" data-aos-delay="400">
                Layanan pet care lengkap dan terpercaya. Mulai dari konsultasi kesehatan, grooming, hingga kebutuhan sehari-hari untuk teman berbulu kesayangan Anda.
              </p>

              <div class="hero-stats mb-4" data-aos="fade-right" data-aos-delay="500">
                <div class="stat-item">
                  <h3><span data-purecounter-start="0" data-purecounter-end="15" data-purecounter-duration="2"
                      class="purecounter"></span>+</h3>
                  <p>Years Experience</p>
                </div>
                <div class="stat-item">
                  <h3><span data-purecounter-start="0" data-purecounter-end="5000" data-purecounter-duration="2"
                      class="purecounter"></span>+</h3>
                  <p>Anabul Bahagia</p>
                </div>
                <div class="stat-item">
                  <h3><span data-purecounter-start="0" data-purecounter-end="50" data-purecounter-duration="2"
                      class="purecounter"></span>+</h3>
                  <p>Dokter Hewan</p>
                </div>
              </div>

              <div class="hero-actions" data-aos="fade-right" data-aos-delay="600">
                @guest
                  {{-- Belum login: Booking + ajakan daftar --}}
                  <a href="{{ route('appointment') }}" class="btn btn-primary">
                    <i class="bi bi-calendar-check me-1"></i>Booking Sekarang
                  </a>
                  <a href="{{ route('register') }}" class="btn btn-outline">
                    <i class="bi bi-person-plus me-1"></i>Daftar Gratis
                  </a>
                @endguest

                @auth
                  {{-- Sudah login: arahkan ke dashboard sesuai role --}}
                  @php
                    $heroDash = match(Auth::user()->role) {
                      'admin'    => route('admin.dashboard'),
                      'doctor'   => route('doctor.dashboard'),
                      'karyawan' => route('karyawan.dashboard'),
                      default    => route('home'),
                    };
                  @endphp
                  <a href="{{ $heroDash }}" class="btn btn-primary">
                    <i class="bi bi-grid me-1"></i>Ke Dashboard
                  </a>
                  <a href="{{ route('appointment') }}" class="btn btn-outline">
                    <i class="bi bi-calendar-check me-1"></i>Booking Sekarang
                  </a>
                @endauth
              </div>

              <div class="emergency-contact" data-aos="fade-right" data-aos-delay="700">
                <div class="emergency-icon">
                  <i class="bi bi-telephone-fill"></i>
                </div>
                <div class="emergency-info">
                  <small>Emergency Hotline</small>
                  <strong>+1 (555) 911-2468</strong>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-6">
            <div class="hero-visual" data-aos="fade-left" data-aos-delay="400">
              <div class="main-image">
                <img src="{{ asset('assets/pawpet/hero/Don\'t tell me I remind you of someone.jpg') }}" alt="PawPet Care" class="img-fluid">
                <div class="floating-card appointment-card">
                  <div class="card-icon">
                    <i class="bi bi-calendar-check"></i>
                  </div>
                  <div class="card-content">
                    <h6>Next Available</h6>
                    <p>Today 2:30 PM</p>
                    <small>Drh. Sarah</small>
                  </div>
                </div>
                <div class="floating-card rating-card">
                  <div class="card-content">
                    <div class="rating-stars">
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star-fill"></i>
                    </div>
                    <h6>4.9/5</h6>
                    <small>1,234 Reviews</small>
                  </div>
                </div>
              </div>
              <div class="background-elements">
                <div class="element element-1"></div>
                <div class="element element-2"></div>
                <div class="element element-3"></div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </section><!-- /Hero Section -->

    <!-- Home About Section -->
    <section id="home-about" class="home-about section">

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row align-items-center">
          <div class="col-lg-6 mb-5 mb-lg-0" data-aos="fade-right" data-aos-delay="200">
            <div class="about-content">
              <h2 class="section-heading">Fasilitas Terbaik untuk Anabul</h2>
              <p class="lead-text">For over two decades, we've been dedicated to providing exceptional healthcare that
                combines cutting-edge medical technology with the personal touch our patients deserve.</p>

              <p>Tim kami selalu siap memastikan setiap hewan peliharaan mendapatkan perawatan komprehensif yang disesuaikan dengan kebutuhan unik mereka.</p>

              <div class="stats-grid">
                <div class="stat-item">
                  <div class="stat-number purecounter" data-purecounter-start="0" data-purecounter-end="15000"
                    data-purecounter-duration="1"></div>
                  <div class="stat-label">Patients Served</div>
                </div>
                <div class="stat-item">
                  <div class="stat-number purecounter" data-purecounter-start="0" data-purecounter-end="25"
                    data-purecounter-duration="1"></div>
                  <div class="stat-label">Years of Excellence</div>
                </div>
                <div class="stat-item">
                  <div class="stat-number purecounter" data-purecounter-start="0" data-purecounter-end="50"
                    data-purecounter-duration="1"></div>
                  <div class="stat-label">Pet Stylist & Vet</div>
                </div>
              </div>

              <div class="cta-section">
                <a href="{{ route('about') }}" class="btn-primary">Learn More About Us</a>
              </div>
            </div>
          </div>

          <div class="col-lg-6" data-aos="fade-left" data-aos-delay="300">
            <div class="about-visual">
              <div class="main-image">
                <img src="{{ asset('assets/pawpet/services/Veterinarian in Clean Uniform Holding a Cute Dog.jpg') }}" alt="Modern medical facility" class="img-fluid">
              </div>
              <div class="floating-card">
                <div class="card-content">
                  <div class="icon">
                    <i class="bi bi-heart-pulse"></i>
                  </div>
                  <div class="card-text">
                    <h4>24/7 Pet Care</h4>
                    <p>Always here when you need us most</p>
                  </div>
                </div>
              </div>
              <div class="experience-badge">
                <div class="badge-content">
                  <span class="years">25+</span>
                  <span class="text">Years of Trusted Care</span>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </section><!-- /Home About Section -->

    <!-- Featured Departments Section -->
    <section id="featured-departments" class="featured-departments section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Layanan Unggulan</h2>
        <p>Solusi lengkap untuk kesehatan dan kebahagiaan teman kecil Anda</p>
      </div><!-- End Section Title -->

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row g-5">

          <div class="col-lg-6" data-aos="zoom-in" data-aos-delay="100">
            <div class="specialty-card">
              <div class="specialty-content">
                <div class="specialty-meta">
                  <span class="specialty-label">Specialized Care</span>
                </div>
                <h3>Perawatan Medis Lengkap</h3>
                <p>Pemeriksaan menyeluruh, penanganan medis, dan saran kesehatan dari ahlinya untuk anabul yang selalu aktif.</p>
                <div class="specialty-features">
                  <span><i class="bi bi-check-circle-fill"></i>24/7 Emergency Cardiac Care</span>
                  <span><i class="bi bi-check-circle-fill"></i>Minimally Invasive Procedures</span>
                </div>
                <a href="{{ route('department-details') }}" class="specialty-link">
                  Explore Cardiology <i class="bi bi-arrow-right"></i>
                </a>
              </div>
              <div class="specialty-visual">
                <img src="{{ asset('assets/pawpet/services/Veterinarian in Clean Uniform Holding a Cute Dog.jpg') }}" alt="Perawatan Medis Lengkap" class="img-fluid">
                <div class="visual-overlay">
                  <i class="bi bi-heart-pulse"></i>
                </div>
              </div>
            </div>
          </div><!-- End Specialty Card -->

          <div class="col-lg-6" data-aos="zoom-in" data-aos-delay="200">
            <div class="specialty-card">
              <div class="specialty-content">
                <div class="specialty-meta">
                  <span class="specialty-label">Expert Care</span>
                </div>
                <h3>Konsultasi Nutrisi & Diet</h3>
                <p>Pemberian saran nutrisi yang tepat untuk menjaga berat badan ideal dan kesehatan pencernaan hewan peliharaan.</p>
                <div class="specialty-features">
                  <span><i class="bi bi-check-circle-fill"></i>Advanced Brain Imaging</span>
                  <span><i class="bi bi-check-circle-fill"></i>Robotic Surgery</span>
                </div>
                <a href="{{ route('department-details') }}" class="specialty-link">
                  Explore Neurology <i class="bi bi-arrow-right"></i>
                </a>
              </div>
              <div class="specialty-visual">
                <img src="{{ asset('assets/pawpet/services/I got Vet! What Should You Be When You Grow Up_.jpg') }}" alt="Konsultasi Nutrisi & Diet" class="img-fluid">
                <div class="visual-overlay">
                  <i class="bi bi-cpu"></i>
                </div>
              </div>
            </div>
          </div><!-- End Specialty Card -->

          <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
            <div class="department-highlight">
              <div class="highlight-icon">
                <i class="bi bi-shield-plus"></i>
              </div>
              <h4>Grooming Premium</h4>
              <p>Perawatan bulu dan kebersihan tubuh dengan sentuhan lembut agar mereka nyaman dan tampil maksimal.</p>
              <ul class="highlight-list">
                <li>Sports Medicine</li>
                <li>Joint Replacement</li>
                <li>Spine Surgery</li>
              </ul>
              <a href="{{ route('department-details') }}" class="highlight-cta">Learn More</a>
            </div>
          </div><!-- End Department Highlight -->

          <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
            <div class="department-highlight">
              <div class="highlight-icon">
                <i class="bi bi-people"></i>
              </div>
              <h4>Penitipan Hewan</h4>
              <p>Layanan penginapan yang aman, bersih, dan nyaman saat Anda harus bepergian.</p>
              <ul class="highlight-list">
                <li>Neonatal Intensive Care</li>
                <li>Developmental Pediatrics</li>
                <li>Pediatric Surgery</li>
              </ul>
              <a href="{{ route('department-details') }}" class="highlight-cta">Learn More</a>
            </div>
          </div><!-- End Department Highlight -->

          <div class="col-lg-4" data-aos="fade-up" data-aos-delay="300">
            <div class="department-highlight">
              <div class="highlight-icon">
                <i class="bi bi-activity"></i>
              </div>
              <h4>Vaksinasi</h4>
              <p>Jadwal imunisasi yang teratur demi mencegah berbagai risiko penyakit berbahaya.</p>
              <ul class="highlight-list">
                <li>Precision Medicine</li>
                <li>Immunotherapy</li>
                <li>Radiation Oncology</li>
              </ul>
              <a href="{{ route('department-details') }}" class="highlight-cta">Learn More</a>
            </div>
          </div><!-- End Department Highlight -->

        </div>

        <div class="emergency-banner" data-aos="fade-up" data-aos-delay="400">
          <div class="row align-items-center">
            <div class="col-lg-8">
              <div class="emergency-content">
                <h3>Emergency Services Available 24/7</h3>
                <p>Our emergency department is equipped with state-of-the-art technology and staffed by board-certified
                  emergency physicians ready to provide immediate care.</p>
              </div>
            </div>
            <div class="col-lg-4 text-lg-end">
              <a href="tel:+15551234567" class="emergency-btn">
                <i class="bi bi-telephone-fill"></i>
                Call Emergency: (555) 123-4567
              </a>
            </div>
          </div>
        </div>

      </div>

    </section><!-- /Featured Departments Section -->

    <!-- Featured Services Section -->
    <section id="featured-services" class="featured-services section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Layanan Terpadu</h2>
        <p>Solusi lengkap untuk kesehatan dan kebahagiaan teman kecil Anda</p>
      </div><!-- End Section Title -->

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row g-0">

          <div class="col-lg-8" data-aos="fade-right" data-aos-delay="200">
            <div class="featured-service-main">
              <div class="service-image-wrapper">
                <img src="{{ asset('assets/pawpet/services/Master the Art of Dog Grooming with Pro Shears.jpg') }}" alt="Premier Healthcare Services" class="img-fluid"
                  loading="lazy">
                <div class="service-overlay">
                  <div class="service-badge">
                    <i class="bi bi-heart-pulse"></i>
                    <span>Pet Care</span>
                  </div>
                </div>
              </div>
              <div class="service-details">
                <h2>Kesehatan dan Perawatan Terbaik</h2>
                <p>Mauris blandit aliquet elit, eget tincidunt nibh pulvinar a. Vestibulum ante ipsum primis in faucibus
                  orci luctus et ultrices posuere cubilia curae donec velit neque.</p>
                <a href="{{ route('services') }}" class="main-cta">Explore Our Services</a>
              </div>
            </div>
          </div>

          <div class="col-lg-4" data-aos="fade-left" data-aos-delay="300">
            <div class="services-sidebar">

              <div class="service-item" data-aos="fade-up" data-aos-delay="400">
                <div class="service-icon-wrapper">
                  <i class="bi bi-capsule"></i>
                </div>
                <div class="service-info">
                  <h4>Perawatan Kulit & Bulu</h4>
                  <p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.</p>
                  <a href="{{ route('services') }}" class="service-link">Learn More</a>
                </div>
              </div>

              <div class="service-item" data-aos="fade-up" data-aos-delay="500">
                <div class="service-icon-wrapper">
                  <i class="bi bi-bandaid"></i>
                </div>
                <div class="service-info">
                  <h4>Tindakan Medis</h4>
                  <p>Donec rutrum congue leo eget malesuada curabitur arcu erat accumsan id imperdiet et porttitor at
                    sem.</p>
                  <a href="{{ route('services') }}" class="service-link">Learn More</a>
                </div>
              </div>

              <div class="service-item" data-aos="fade-up" data-aos-delay="600">
                <div class="service-icon-wrapper">
                  <i class="bi bi-activity"></i>
                </div>
                <div class="service-info">
                  <h4>Laboratorium Hewan</h4>
                  <p>Vestibulum ac diam sit amet quam vehicula elementum sed sit amet dui cras ultricies ligula sed
                    magna.</p>
                  <a href="{{ route('services') }}" class="service-link">Learn More</a>
                </div>
              </div>

            </div>
          </div>

        </div>

        <div class="specialties-grid" data-aos="fade-up" data-aos-delay="300">
          <div class="row align-items-center">

            <div class="col-lg-3 col-md-6">
              <div class="specialty-card">
                <div class="specialty-image">
                  <img src="{{ asset('assets/pawpet/icons/icon-pet.jpeg') }}" alt="Konsultasi Dokter Hewan" class="img-fluid" loading="lazy">
                </div>
                <div class="specialty-content">
                  <h5>Konsultasi Dokter Hewan</h5>
                  <span>Pemeriksaan rutin</span>
                </div>
              </div>
            </div>

            <div class="col-lg-3 col-md-6">
              <div class="specialty-card">
                <div class="specialty-image">
                  <img src="{{ asset('assets/pawpet/icons/icon-sevices.jpeg') }}" alt="Grooming" class="img-fluid" loading="lazy">
                </div>
                <div class="specialty-content">
                  <h5>Grooming</h5>
                  <span>Spa & potong kuku</span>
                </div>
              </div>
            </div>

            <div class="col-lg-3 col-md-6">
              <div class="specialty-card">
                <div class="specialty-image">
                  <img src="{{ asset('assets/pawpet/icons/icon-shopping.jpeg') }}" alt="Pet Care" class="img-fluid" loading="lazy">
                </div>
                <div class="specialty-content">
                  <h5>Pet Care</h5>
                  <span>Kebutuhan harian</span>
                </div>
              </div>
            </div>

            <div class="col-lg-3 col-md-6">
              <div class="specialty-card">
                <div class="specialty-image">
                  <img src="{{ asset('assets/pawpet/icons/icon-product.jpeg') }}" alt="Advanced Tech" class="img-fluid" loading="lazy">
                </div>
                <div class="specialty-content">
                  <h5>Mudah Booking</h5>
                  <span>Jadwalkan via web</span>
                </div>
              </div>
            </div>

          </div>
        </div>

      </div>

    </section><!-- /Featured Services Section -->

    <!-- Find A Doctor Section -->
    <section id="find-a-doctor" class="find-a-doctor section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>PawPet Shop</h2>
        <p>Solusi lengkap untuk kesehatan dan kebahagiaan teman kecil Anda</p>
      </div><!-- End Section Title -->

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row justify-content-center mb-5" data-aos="fade-up" data-aos-delay="200">
          <div class="col-lg-8 text-center">
            <div class="search-section">
              <h3 class="search-title">Temukan Kebutuhan Anabul Anda</h3>
              <p class="search-subtitle">Berbagai pilihan makanan, aksesoris, dan mainan berkualitas
              </p>
              <form class="search-form" action="#!" method="#">
                <div class="search-input-group">
                  <div class="input-wrapper">
                    <i class="bi bi-person"></i>
                    <input type="text" class="form-control" name="doctor_name" placeholder="Cari produk">
                  </div>
                  <div class="select-wrapper">
                    <i class="bi bi-heart-pulse"></i>
                    <select class="form-select" name="specialty">
                      <option value="">All Specialties</option>
                      <option value="cardiology">Cardiology</option>
                      <option value="neurology">Neurology</option>
                      <option value="orthopedics">Orthopedics</option>
                      <option value="pediatrics">Pediatrics</option>
                      <option value="dermatology">Dermatology</option>
                      <option value="oncology">Oncology</option>
                    </select>
                  </div>
                  <button type="submit" class="search-btn">
                    <i class="bi bi-search"></i>
                    Cari Produk
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>

        <div class="doctors-grid" data-aos="fade-up" data-aos-delay="300">
          <div class="doctor-profile" data-aos="zoom-in" data-aos-delay="100">
            <div class="profile-header">
              <div class="doctor-avatar">
                <img src="{{ asset('assets/pawpet/products/Dog pedigree packs.jpg') }}" alt="Premium Dog Food" class="img-fluid">
                <div class="status-indicator available"></div>
              </div>
              <div class="doctor-details">
                <h4>Premium Dog Food</h4>
                <span class="specialty-tag">Makanan Anjing</span>
                <div class="experience-info">
                  <i class="bi bi-award"></i>
                  <span>Rp 125.000</span>
                </div>
              </div>
            </div>
            <div class="rating-section">
              <div class="stars">
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
              </div>
              <span class="rating-score">4.9</span>
              <span class="review-count">(127 reviews)</span>
            </div>
            <div class="action-buttons">
              <a href="{{ route('doctors') }}" class="btn-secondary">View Details</a>
              <a href="{{ route('appointment') }}" class="btn-primary">Book Now</a>
            </div>
          </div><!-- End Doctor Profile -->

          <div class="doctor-profile" data-aos="zoom-in" data-aos-delay="200">
            <div class="profile-header">
              <div class="doctor-avatar">
                <img src="{{ asset('assets/pawpet/products/Ultra Moisturizing All Natural & Organic Paw Balm for Dogs - 0_15oz.jpg') }}" alt="Organic Paw Balm" class="img-fluid">
                <div class="status-indicator busy"></div>
              </div>
              <div class="doctor-details">
                <h4>Organic Paw Balm</h4>
                <span class="specialty-tag">Perawatan Tubuh</span>
                <div class="experience-info">
                  <i class="bi bi-award"></i>
                  <span>Rp 85.000</span>
                </div>
              </div>
            </div>
            <div class="rating-section">
              <div class="stars">
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-half"></i>
              </div>
              <span class="rating-score">4.8</span>
              <span class="review-count">(89 reviews)</span>
            </div>
            <div class="action-buttons">
              <a href="{{ route('doctors') }}" class="btn-secondary">View Details</a>
              <a href="{{ route('appointment') }}" class="btn-primary">Schedule</a>
            </div>
          </div><!-- End Doctor Profile -->

          <div class="doctor-profile" data-aos="zoom-in" data-aos-delay="300">
            <div class="profile-header">
              <div class="doctor-avatar">
                <img src="{{ asset('assets/pawpet/products/Things for Dogs_ Must-Have Products for Canine Comfort and Happiness.jpg') }}" alt="Cozy Pet Bed" class="img-fluid">
                <div class="status-indicator available"></div>
              </div>
              <div class="doctor-details">
                <h4>Cozy Pet Bed</h4>
                <span class="specialty-tag">Tempat Tidur</span>
                <div class="experience-info">
                  <i class="bi bi-award"></i>
                  <span>Rp 250.000</span>
                </div>
              </div>
            </div>
            <div class="rating-section">
              <div class="stars">
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
              </div>
              <span class="rating-score">5.0</span>
              <span class="review-count">(203 reviews)</span>
            </div>
            <div class="action-buttons">
              <a href="{{ route('doctors') }}" class="btn-secondary">View Details</a>
              <a href="{{ route('appointment') }}" class="btn-primary">Book Now</a>
            </div>
          </div><!-- End Doctor Profile -->

          <div class="doctor-profile" data-aos="zoom-in" data-aos-delay="400">
            <div class="profile-header">
              <div class="doctor-avatar">
                <img src="{{ asset('assets/pawpet/products/download.jpg') }}" alt="Cat Accessories Bundle" class="img-fluid">
                <div class="status-indicator offline"></div>
              </div>
              <div class="doctor-details">
                <h4>Cat Accessories Bundle</h4>
                <span class="specialty-tag">Grooming Premium</span>
                <div class="experience-info">
                  <i class="bi bi-award"></i>
                  <span>Rp 65.000</span>
                </div>
              </div>
            </div>
            <div class="rating-section">
              <div class="stars">
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-half"></i>
              </div>
              <span class="rating-score">4.7</span>
              <span class="review-count">(156 reviews)</span>
            </div>
            <div class="action-buttons">
              <a href="{{ route('doctors') }}" class="btn-secondary">View Details</a>
              <a href="{{ route('appointment') }}" class="btn-primary">Schedule</a>
            </div>
          </div><!-- End Doctor Profile -->

          <div class="doctor-profile" data-aos="zoom-in" data-aos-delay="500">
            <div class="profile-header">
              <div class="doctor-avatar">
                <img src="{{ asset('assets/img/health/staff-11.webp') }}" alt="Dr. Victoria Torres" class="img-fluid">
                <div class="status-indicator available"></div>
              </div>
              <div class="doctor-details">
                <h4>Dr. Victoria Torres</h4>
                <span class="specialty-tag">Dermatology Care</span>
                <div class="experience-info">
                  <i class="bi bi-award"></i>
                  <span>9 years experience</span>
                </div>
              </div>
            </div>
            <div class="rating-section">
              <div class="stars">
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star"></i>
              </div>
              <span class="rating-score">4.5</span>
              <span class="review-count">(74 reviews)</span>
            </div>
            <div class="action-buttons">
              <a href="#!" class="btn-secondary">View Details</a>
              <a href="#!" class="btn-primary">Book Now</a>
            </div>
          </div><!-- End Doctor Profile -->

          <div class="doctor-profile" data-aos="zoom-in" data-aos-delay="600">
            <div class="profile-header">
              <div class="doctor-avatar">
                <img src="{{ asset('assets/img/health/staff-14.webp') }}" alt="Dr. Benjamin Lee" class="img-fluid">
                <div class="status-indicator available"></div>
              </div>
              <div class="doctor-details">
                <h4>Dr. Benjamin Lee</h4>
                <span class="specialty-tag">Oncology Treatment</span>
                <div class="experience-info">
                  <i class="bi bi-award"></i>
                  <span>19 years experience</span>
                </div>
              </div>
            </div>
            <div class="rating-section">
              <div class="stars">
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
              </div>
              <span class="rating-score">4.9</span>
              <span class="review-count">(194 reviews)</span>
            </div>
            <div class="action-buttons">
              <a href="{{ route('doctors') }}" class="btn-secondary">View Details</a>
              <a href="{{ route('appointment') }}" class="btn-primary">Schedule</a>
            </div>
          </div><!-- End Doctor Profile -->

        </div>

        <div class="text-center mt-5" data-aos="fade-up" data-aos-delay="700">
          <a href="{{ route('doctors') }}" class="btn-view-all">
            View All Doctors
            <i class="bi bi-arrow-right"></i>
          </a>
        </div>

      </div>

    </section><!-- /Find A Doctor Section -->

    <!-- Call To Action Section -->
    <section id="call-to-action" class="call-to-action section light-background">

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="hero-content">
          <div class="row align-items-center">

            <div class="col-lg-6">
              <div class="content-wrapper" data-aos="fade-up" data-aos-delay="200">
                <h1>Siap Memberikan yang Terbaik untuk Anabul?</h1>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore
                  et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation.</p>

                <div class="cta-wrapper">
                  <a href="{{ route('appointment') }}" class="primary-cta">
                    <span>Schedule Consultation</span>
                    <i class="bi bi-arrow-right"></i>
                  </a>
                  <a href="{{ route('services') }}" class="secondary-cta">
                    <span>Explore Services</span>
                    <i class="bi bi-arrow-right"></i>
                  </a>
                </div>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="image-container" data-aos="fade-left" data-aos-delay="300">
                <img src="{{ asset('assets/pawpet/services/Veterinarian in Clean Uniform Holding a Cute Dog.jpg') }}" alt="Medical Excellence" class="img-fluid">
              </div>
            </div>

          </div>
        </div>

        <div class="features-section">

          <div class="row g-0">

            <div class="col-lg-4">
              <div class="feature-block" data-aos="fade-up" data-aos-delay="200">
                <div class="feature-icon">
                  <i class="bi bi-shield-check"></i>
                </div>
                <h3>Mudah Booking</h3>
                <p>Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est
                  laborum.</p>
              </div>
            </div>

            <div class="col-lg-4">
              <div class="feature-block" data-aos="fade-up" data-aos-delay="300">
                <div class="feature-icon">
                  <i class="bi bi-clock"></i>
                </div>
                <h3>24/7 Availability</h3>
                <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur
                  excepteur.</p>
              </div>
            </div>

            <div class="col-lg-4">
              <div class="feature-block" data-aos="fade-up" data-aos-delay="400">
                <div class="feature-icon">
                  <i class="bi bi-people"></i>
                </div>
                <h3>Expert Team</h3>
                <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium
                  totam rem.</p>
              </div>
            </div>

          </div>

        </div>

        <div class="contact-block">
          <div class="row">

            <div class="col-lg-8">
              <div class="contact-content" data-aos="fade-up" data-aos-delay="200">
                <h2>Need Immediate Medical Assistance?</h2>
                <p>Tim kami selalu siap sedia memberikan pelayanan terbaik untuk anabul Anda, kapanpun dibutuhkan.
                  you need it most.</p>
              </div>
            </div>

            <div class="col-lg-4">
              <div class="contact-actions" data-aos="fade-up" data-aos-delay="300">
                <a href="tel:5551234567" class="emergency-call">
                  <i class="bi bi-telephone"></i>
                  <span>(555) 123-4567</span>
                </a>
                <a href="{{ route('contact') }}" class="contact-link">Find Location</a>
              </div>
            </div>

          </div>
        </div>

      </div>

    </section><!-- /Call To Action Section -->

  </main>

@endsection