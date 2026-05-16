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
                  <h3><span data-purecounter-start="0" data-purecounter-end="{{ $stats['totalProducts'] ?? 0 }}" data-purecounter-duration="2"
                      class="purecounter"></span>+</h3>
                  <p>Produk Tersedia</p>
                </div>
                <div class="stat-item">
                  <h3><span data-purecounter-start="0" data-purecounter-end="{{ $stats['totalServices'] ?? 0 }}" data-purecounter-duration="2"
                      class="purecounter"></span>+</h3>
                  <p>Layanan Aktif</p>
                </div>
                <div class="stat-item">
                  <h3><span data-purecounter-start="0" data-purecounter-end="{{ $stats['totalDoctors'] ?? 0 }}" data-purecounter-duration="2"
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
                  <strong>+62 811 2233 4455</strong>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-6">
            <div class="hero-visual" data-aos="fade-left" data-aos-delay="400">
              <div class="main-image">
                <img src="{{ asset('assets/pawpet/hero/banhom.jpg') }}" alt="PawPet Care" class="img-fluid">
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
              <p class="lead-text">Selama lebih dari dua dekade, kami berkomitmen untuk memberikan layanan kesehatan hewan yang luar biasa
                dengan memadukan teknologi medis terkini dan sentuhan personal yang layak didapatkan anabul Anda.</p>

              <p>Tim kami selalu siap memastikan setiap hewan peliharaan mendapatkan perawatan komprehensif yang disesuaikan dengan kebutuhan unik mereka.</p>

              <div class="stats-grid">
                <div class="stat-item">
                  <div class="stat-number purecounter" data-purecounter-start="0" data-purecounter-end="{{ $stats['totalProducts'] ?? 0 }}"
                    data-purecounter-duration="1"></div>
                  <div class="stat-label">Produk Tersedia</div>
                </div>
                <div class="stat-item">
                  <div class="stat-number purecounter" data-purecounter-start="0" data-purecounter-end="{{ $stats['totalServices'] ?? 0 }}"
                    data-purecounter-duration="1"></div>
                  <div class="stat-label">Layanan Aktif</div>
                </div>
                <div class="stat-item">
                  <div class="stat-number purecounter" data-purecounter-start="0" data-purecounter-end="{{ $stats['totalDoctors'] ?? 0 }}"
                    data-purecounter-duration="1"></div>
                  <div class="stat-label">Dokter Hewan</div>
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
                  <span><i class="bi bi-check-circle-fill"></i>Pemeriksaan Kesehatan Menyeluruh</span>
                  <span><i class="bi bi-check-circle-fill"></i>Penanganan Darurat 24/7</span>
                </div>
                <a href="{{ route('department-details') }}" class="specialty-link">
                  Selengkapnya <i class="bi bi-arrow-right"></i>
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
                  <span><i class="bi bi-check-circle-fill"></i>Konsultasi Diet Khusus</span>
                  <span><i class="bi bi-check-circle-fill"></i>Suplemen & Vitamin Hewan</span>
                </div>
                <a href="{{ route('department-details') }}" class="specialty-link">
                  Selengkapnya <i class="bi bi-arrow-right"></i>
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
                <li>Mandi & Blow Dry</li>
                <li>Potong Kuku & Bulu</li>
                <li>Spa & Aromaterapi</li>
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
                <li>Kamar Nyaman & Bersih</li>
                <li>Pantauan Berkala</li>
                <li>Aktivitas Harian</li>
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
                <li>Vaksin Dasar & Lanjutan</li>
                <li>Jadwal Imunisasi Teratur</li>
                <li>Sertifikat Vaksin Resmi</li>
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
              <a href="tel:+6281122334455" class="emergency-btn">
                <i class="bi bi-telephone-fill"></i>
                Hubungi Darurat: +62 811 2233 4455
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
                <p>Kami menyediakan layanan kesehatan menyeluruh mulai dari pemeriksaan rutin, grooming profesional, hingga kebutuhan nutrisi hewan peliharaan Anda.</p>
                <a href="{{ route('services') }}" class="main-cta">Jelajahi Layanan Kami</a>
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
                  <p>Perawatan khusus untuk menjaga kesehatan kulit dan keindahan bulu anabul kesayangan Anda.</p>
                  <a href="{{ route('services') }}" class="service-link">Learn More</a>
                </div>
              </div>

              <div class="service-item" data-aos="fade-up" data-aos-delay="500">
                <div class="service-icon-wrapper">
                  <i class="bi bi-bandaid"></i>
                </div>
                <div class="service-info">
                  <h4>Tindakan Medis</h4>
                  <p>Penanganan medis profesional oleh dokter hewan berpengalaman untuk berbagai kondisi kesehatan.</p>
                  <a href="{{ route('services') }}" class="service-link">Learn More</a>
                </div>
              </div>

              <div class="service-item" data-aos="fade-up" data-aos-delay="600">
                <div class="service-icon-wrapper">
                  <i class="bi bi-activity"></i>
                </div>
                <div class="service-info">
                  <h4>Laboratorium Hewan</h4>
                  <p>Pemeriksaan laboratorium lengkap untuk diagnosis yang akurat dan penanganan yang tepat.</p>
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

    <!-- PawPet Shop Section -->
    <section id="shop" class="find-a-doctor section">

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
            </div>
          </div>
        </div>

        <div class="doctors-grid" data-aos="fade-up" data-aos-delay="300">
          @forelse($featuredProducts as $product)
          <div class="doctor-profile" data-aos="zoom-in" data-aos-delay="{{ 100 + ($loop->index * 100) }}">
            <div class="profile-header">
              <div class="doctor-avatar">
                <img src="{{ $product->image_url }}" alt="{{ $product->nama_barang }}" class="img-fluid">
                <div class="status-indicator {{ $product->stok > 0 ? 'available' : 'offline' }}"></div>
              </div>
              <div class="doctor-details">
                <h4>{{ $product->nama_barang }}</h4>
                <span class="specialty-tag">{{ $product->kategori ?? 'Umum' }}</span>
                <div class="experience-info">
                  <i class="bi bi-award"></i>
                  <span>Rp {{ number_format($product->harga, 0, ',', '.') }}</span>
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
              <span class="rating-score">Stok: {{ $product->stok }}</span>
            </div>
            <div class="action-buttons">
              <a href="{{ route('services') }}" class="btn-secondary">View Details</a>
              <a href="{{ route('appointment') }}" class="btn-primary">Order</a>
            </div>
          </div><!-- End Product Card -->
          @empty
          <div class="text-center py-5">
            <i class="bi bi-box-seam" style="font-size: 3rem; color: var(--accent-color);"></i>
            <h4 class="mt-3">Produk segera hadir</h4>
            <p class="text-muted">Kami sedang menyiapkan produk terbaik untuk anabul Anda.</p>
          </div>
          @endforelse
        </div>

        <div class="text-center mt-5" data-aos="fade-up" data-aos-delay="700">
          <a href="{{ route('services') }}" class="btn-view-all">
            Lihat Semua Produk
            <i class="bi bi-arrow-right"></i>
          </a>
        </div>

      </div>

    </section><!-- /PawPet Shop Section -->

    <!-- Call To Action Section -->
    <section id="call-to-action" class="call-to-action section light-background">

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="hero-content">
          <div class="row align-items-center">

            <div class="col-lg-6">
              <div class="content-wrapper" data-aos="fade-up" data-aos-delay="200">
                <h1>Siap Memberikan yang Terbaik untuk Anabul?</h1>
                <p>Dari konsultasi dokter hewan, grooming profesional, hingga penitipan hewan yang aman — semua layanan terbaik untuk anabul kesayangan Anda tersedia di PawPet.</p>

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
                <p>Jadwalkan kunjungan dan layanan untuk anabul Anda dengan mudah melalui sistem booking online kami.</p>
              </div>
            </div>

            <div class="col-lg-4">
              <div class="feature-block" data-aos="fade-up" data-aos-delay="300">
                <div class="feature-icon">
                  <i class="bi bi-clock"></i>
                </div>
                <h3>24/7 Availability</h3>
                <p>Tim kami siap sedia memberikan pelayanan darurat kapanpun dibutuhkan, termasuk di luar jam kerja.</p>
              </div>
            </div>

            <div class="col-lg-4">
              <div class="feature-block" data-aos="fade-up" data-aos-delay="400">
                <div class="feature-icon">
                  <i class="bi bi-people"></i>
                </div>
                <h3>Tim Profesional</h3>
                <p>Dokter hewan berpengalaman dan groomer profesional kami siap memberikan perawatan terbaik untuk anabul Anda.</p>
              </div>
            </div>

          </div>

        </div>

        <div class="contact-block">
          <div class="row">

            <div class="col-lg-8">
              <div class="contact-content" data-aos="fade-up" data-aos-delay="200">
                <h2>Butuh Bantuan Darurat untuk Anabul?</h2>
                <p>Tim kami selalu siap sedia memberikan pelayanan terbaik untuk anabul Anda, kapanpun dibutuhkan.</p>
              </div>
            </div>

            <div class="col-lg-4">
              <div class="contact-actions" data-aos="fade-up" data-aos-delay="300">
                <a href="tel:+6281122334455" class="emergency-call">
                  <i class="bi bi-telephone"></i>
                  <span>+62 811 2233 4455</span>
                </a>
                <a href="{{ route('contact') }}" class="contact-link">Temukan Lokasi</a>
              </div>
            </div>

          </div>
        </div>

      </div>

    </section><!-- /Call To Action Section -->

  </main>

@endsection