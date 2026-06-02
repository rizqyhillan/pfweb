@extends('layouts.front')

@section('title', 'PawPet — Teman Setia Perawatan Anabul')

@section('meta_description', 'PawPet adalah ekosistem perawatan hewan peliharaan modern terintegrasi dengan web dashboard admin dan aplikasi mobile untuk rekam medis, grooming, penitipan, dan pemesanan layanan.')

@section('content')
<main class="main" style="font-family: 'Quicksand', sans-serif; color: #2d2d2d; background-color: #fff;">

  <!-- =========================================================================
     HERO SECTION
     ========================================================================= -->
  <section id="hero" class="hero-section d-flex align-items-center" style="min-height: 90vh; padding-top: 120px; padding-bottom: 80px; background: radial-gradient(circle at 80% 20%, #fffbeb 0%, #ffffff 100%); overflow: hidden; position: relative;">
    <div class="container">
      <div class="row align-items-center">
        
        <div class="col-lg-6" data-aos="fade-right" data-aos-duration="800">
          <div class="hero-content-wrapper">
            <!-- Trust Badge -->
            <div class="d-inline-flex align-items-center gap-2 px-3 py-2 mb-4" style="background: rgba(245, 158, 11, 0.1); border-radius: 50px;">
              <i class="bi bi-heart-fill" style="color: #f59e0b; font-size: 0.95rem;"></i>
              <span style="font-weight: 700; font-size: 0.85rem; color: #d97706; text-transform: uppercase; letter-spacing: 0.5px;">Ecosystem Perawatan Anabul Terpadu</span>
            </div>

            <!-- Main Heading -->
            <h1 style="font-size: clamp(2.5rem, 5vw, 3.8rem); font-weight: 800; line-height: 1.2; color: #1e1b4b; margin-bottom: 24px;">
              Teman Setia, <br>
              <span style="color: #f59e0b; position: relative; display: inline-block;">
                Perawatan Terbaik
                <span style="position: absolute; bottom: 4px; left: 0; width: 100%; height: 8px; background: rgba(245, 158, 11, 0.15); z-index: -1; border-radius: 4px;"></span>
              </span> <br>
              untuk Anabul
            </h1>

            <!-- Description -->
            <p style="font-size: 1.15rem; line-height: 1.7; color: #64748b; margin-bottom: 35px; max-width: 520px;">
              Kelola rekam medis, pemesanan grooming, penitipan hewan, dan jadwal konsultasi dokter secara praktis. Sistem manajemen terintegrasi melalui Web Dashboard Klinik dan Aplikasi Mobile Pemilik Anabul.
            </p>

            <!-- Single CTA Button -->
            <div class="hero-actions mb-5">
              <a href="https://mega.nz/file/ex5FRTZa#B8tYmaDeDlQzp6C2-qxD4dvOUUBHK9hsCt244u83gvI" target="_blank" class="btn-hero-download" style="display: inline-flex; align-items: center; background: #f59e0b; color: #fff; border: none; border-radius: 50px; padding: 15px 35px; font-weight: 700; font-size: 1.1rem; text-decoration: none; box-shadow: 0 10px 25px rgba(245, 158, 11, 0.35); transition: all 0.3s;">
                <i class="bi bi-android2 me-2" style="font-size: 1.3rem;"></i> Download APK Android
              </a>
            </div>
          </div>
        </div>

        <div class="col-lg-6" data-aos="fade-left" data-aos-duration="800">
          <div class="hero-visual-wrapper position-relative text-center mt-5 mt-lg-0">
            <!-- Background Decorative Glow -->
            <div class="glow-element" style="position: absolute; top: 50%; left: 50%; transform: translate(-translate(-50%, -50%)); width: 450px; height: 450px; background: rgba(245, 158, 11, 0.12); filter: blur(80px); border-radius: 50%; z-index: -1;"></div>
            
            <!-- Hero Illustration Main Image -->
            <img src="{{ asset('assets/pawpet/hero/banhom.jpg') }}" alt="PawPet Care" class="img-fluid hero-illustration-img" style="max-height: 520px; border-radius: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.06); border: 8px solid #fff; transition: transform 0.3s ease;">
          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- =========================================================================
     ABOUT SECTION (TENTANG PAWPET)
     ========================================================================= -->
  <section id="about" class="about-section" style="padding: 100px 0; background-color: #fffaf5;">
    <div class="container">
      <div class="row align-items-center">
        
        <div class="col-lg-6" data-aos="fade-right" data-aos-duration="800">
          <div class="about-visual position-relative text-center mb-5 mb-lg-0">
            <img src="{{ asset('assets/pawpet/services/Veterinarian in Clean Uniform Holding a Cute Dog.jpg') }}" alt="Tentang PawPet" class="img-fluid" style="border-radius: 24px; box-shadow: 0 15px 35px rgba(0,0,0,0.08); max-height: 480px;">
          </div>
        </div>

        <div class="col-lg-6" data-aos="fade-left" data-aos-duration="800">
          <div class="about-content-wrapper ps-lg-5">
            <div class="d-inline-block px-3 py-1 mb-3" style="background: rgba(245, 158, 11, 0.1); border-radius: 50px;">
              <span style="font-weight: 700; font-size: 0.8rem; color: #f59e0b; text-transform: uppercase;">Tentang PawPet</span>
            </div>
            <h2 style="font-size: 2.5rem; font-weight: 800; color: #1e1b4b; margin-bottom: 25px;">Ecosystem Dua Platform, Satu Solusi Terintegrasi</h2>
            <p style="font-size: 1.1rem; line-height: 1.8; color: #64748b; margin-bottom: 20px;">
              PawPet dirancang khusus untuk memadukan kenyamanan bagi pemilik hewan peliharaan (melalui <strong>Aplikasi Mobile Android</strong>) dengan keandalan operasional klinik (melalui <strong>Web Dashboard Admin</strong>).
            </p>
            <p style="font-size: 1.1rem; line-height: 1.8; color: #64748b; margin-bottom: 30px;">
              Setiap catatan medis, jadwal vaksinasi, status grooming, dan reservasi penitipan hewan disinkronkan secara real-time. Dokter hewan dapat dengan mudah memperbarui data medis via web, dan hasilnya langsung dapat diakses oleh pemilik anabul di smartphone mereka secara transparan dan instan.
            </p>
            
            <div class="row g-4">
              <div class="col-sm-6">
                <div class="d-flex align-items-center gap-3">
                  <div class="icon-wrap" style="width: 48px; height: 48px; border-radius: 50%; background: #fff; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 10px rgba(0,0,0,0.05); color: #f59e0b; font-size: 1.3rem;">
                    <i class="bi bi-phone"></i>
                  </div>
                  <div>
                    <h5 style="font-weight: 700; color: #1e1b4b; margin-bottom: 2px; font-size: 1rem;">Flutter Mobile App</h5>
                    <p style="font-size: 0.85rem; color: #64748b; margin-bottom: 0;">Khusus Pemilik Anabul</p>
                  </div>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="d-flex align-items-center gap-3">
                  <div class="icon-wrap" style="width: 48px; height: 48px; border-radius: 50%; background: #fff; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 10px rgba(0,0,0,0.05); color: #f59e0b; font-size: 1.3rem;">
                    <i class="bi bi-laptop"></i>
                  </div>
                  <div>
                    <h5 style="font-weight: 700; color: #1e1b4b; margin-bottom: 2px; font-size: 1rem;">Laravel Web Admin</h5>
                    <p style="font-size: 0.85rem; color: #64748b; margin-bottom: 0;">Khusus Dokter & Staff</p>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- =========================================================================
     FEATURES SECTION (FITUR UTAMA)
     ========================================================================= -->
  <section id="fitur" class="fitur-section" style="padding: 100px 0; background-color: #fff;">
    <div class="container">
      
      <!-- Section Title -->
      <div class="text-center mb-5" data-aos="fade-up" data-aos-duration="800">
        <div class="d-inline-block px-3 py-1 mb-3" style="background: rgba(245, 158, 11, 0.1); border-radius: 50px;">
          <span style="font-weight: 700; font-size: 0.8rem; color: #f59e0b; text-transform: uppercase;">Fitur Utama</span>
        </div>
        <h2 style="font-size: 2.5rem; font-weight: 800; color: #1e1b4b;">Layanan Unggulan PawPet</h2>
        <p style="font-size: 1.1rem; color: #64748b; max-width: 600px; margin: 15px auto 0;">Semua fitur yang dirancang secara detail dan matang untuk memudahkan perawatan harian dan pemantauan kesehatan anabul.</p>
      </div>

      <!-- Features Grid (6 Informational Cards) -->
      <div class="row g-4">
        
        <!-- Card 1 -->
        <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100" data-aos-duration="800">
          <div class="feature-card-clean" style="height: 100%; padding: 40px 30px; background: #ffffff; border: 1px solid rgba(0, 0, 0, 0.05); border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.02); transition: all 0.3s ease;">
            <div class="icon-box-feature" style="width: 60px; height: 60px; border-radius: 16px; background: #fff9f2; color: #f59e0b; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; margin-bottom: 25px; transition: all 0.3s ease;">
              <i class="bi bi-file-earmark-medical"></i>
            </div>
            <h4 style="font-weight: 700; color: #1e1b4b; margin-bottom: 15px; font-size: 1.25rem;">Pencatatan Rekam Medis</h4>
            <p style="font-size: 0.95rem; line-height: 1.6; color: #64748b; margin-bottom: 0;">
              Riwayat medis, data vaksinasi, resep dokter, dan riwayat alergi anabul tercatat dengan rapi, aman, dan dapat diakses kapan saja oleh pemilik maupun dokter hewan yang berwenang.
            </p>
          </div>
        </div>

        <!-- Card 2 -->
        <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200" data-aos-duration="800">
          <div class="feature-card-clean" style="height: 100%; padding: 40px 30px; background: #ffffff; border: 1px solid rgba(0, 0, 0, 0.05); border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.02); transition: all 0.3s ease;">
            <div class="icon-box-feature" style="width: 60px; height: 60px; border-radius: 16px; background: #fff9f2; color: #f59e0b; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; margin-bottom: 25px; transition: all 0.3s ease;">
              <i class="bi bi-calendar3"></i>
            </div>
            <h4 style="font-weight: 700; color: #1e1b4b; margin-bottom: 15px; font-size: 1.25rem;">Konsultasi Dokter Hewan</h4>
            <p style="font-size: 0.95rem; line-height: 1.6; color: #64748b; margin-bottom: 0;">
              Lupakan antrean panjang di klinik. Lakukan pemesanan janji temu konsultasi secara online dengan dokter hewan terbaik dan pilih waktu penanganan yang paling sesuai bagi Anda.
            </p>
          </div>
        </div>

        <!-- Card 3 -->
        <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="300" data-aos-duration="800">
          <div class="feature-card-clean" style="height: 100%; padding: 40px 30px; background: #ffffff; border: 1px solid rgba(0, 0, 0, 0.05); border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.02); transition: all 0.3s ease;">
            <div class="icon-box-feature" style="width: 60px; height: 60px; border-radius: 16px; background: #fff9f2; color: #f59e0b; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; margin-bottom: 25px; transition: all 0.3s ease;">
              <i class="bi bi-scissors"></i>
            </div>
            <h4 style="font-weight: 700; color: #1e1b4b; margin-bottom: 15px; font-size: 1.25rem;">Grooming Profesional</h4>
            <p style="font-size: 0.95rem; line-height: 1.6; color: #64748b; margin-bottom: 0;">
              Jadwalkan grooming rutin dengan groomer terlatih. Dari mandi bersih, potong bulu stylish, potong kuku, hingga spa pembersihan telinga agar anabul Anda wangi dan sehat.
            </p>
          </div>
        </div>

        <!-- Card 4 -->
        <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="400" data-aos-duration="800">
          <div class="feature-card-clean" style="height: 100%; padding: 40px 30px; background: #ffffff; border: 1px solid rgba(0, 0, 0, 0.05); border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.02); transition: all 0.3s ease;">
            <div class="icon-box-feature" style="width: 60px; height: 60px; border-radius: 16px; background: #fff9f2; color: #f59e0b; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; margin-bottom: 25px; transition: all 0.3s ease;">
              <i class="bi bi-house-heart"></i>
            </div>
            <h4 style="font-weight: 700; color: #1e1b4b; margin-bottom: 15px; font-size: 1.25rem;">Penitipan Hewan Nyaman</h4>
            <p style="font-size: 0.95rem; line-height: 1.6; color: #64748b; margin-bottom: 0;">
              Fasilitas boarding / hotel anabul yang luas, bersih, dan higienis. Memberikan ketenangan penuh saat Anda harus bepergian dengan jaminan pengawasan harian oleh tim kami.
            </p>
          </div>
        </div>

        <!-- Card 5 -->
        <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="500" data-aos-duration="800">
          <div class="feature-card-clean" style="height: 100%; padding: 40px 30px; background: #ffffff; border: 1px solid rgba(0, 0, 0, 0.05); border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.02); transition: all 0.3s ease;">
            <div class="icon-box-feature" style="width: 60px; height: 60px; border-radius: 16px; background: #fff9f2; color: #f59e0b; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; margin-bottom: 25px; transition: all 0.3s ease;">
              <i class="bi bi-bag-heart"></i>
            </div>
            <h4 style="font-weight: 700; color: #1e1b4b; margin-bottom: 15px; font-size: 1.25rem;">PawPet Shop</h4>
            <p style="font-size: 0.95rem; line-height: 1.6; color: #64748b; margin-bottom: 0;">
              PawPet Shop membantu pemilik anabul membeli makanan, vitamin, aksesoris, dan kebutuhan harian hewan kesayangan Anda secara praktis dan langsung melalui aplikasi mobile.
            </p>
          </div>
        </div>

        <!-- Card 6 -->
        <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="600" data-aos-duration="800">
          <div class="feature-card-clean" style="height: 100%; padding: 40px 30px; background: #ffffff; border: 1px solid rgba(0, 0, 0, 0.05); border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.02); transition: all 0.3s ease;">
            <div class="icon-box-feature" style="width: 60px; height: 60px; border-radius: 16px; background: #fff9f2; color: #f59e0b; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; margin-bottom: 25px; transition: all 0.3s ease;">
              <i class="bi bi-grid-1x2"></i>
            </div>
            <h4 style="font-weight: 700; color: #1e1b4b; margin-bottom: 15px; font-size: 1.25rem;">Manajemen Dashboard Web</h4>
            <p style="font-size: 0.95rem; line-height: 1.6; color: #64748b; margin-bottom: 0;">
              Panel khusus bagi administrator klinik untuk memanajemen transaksi pesanan, jadwal dokter, rekam medis yang diinput, ketersediaan kamar penitipan, hingga monitoring stok produk.
            </p>
          </div>
        </div>

      </div>

    </div>
  </section>

  <!-- =========================================================================
     APP SHOWCASE SECTION (SCREENSHOT NYATA APLIKASI)
     ========================================================================= -->
  <section id="showcase" class="showcase-section" style="padding: 100px 0; background-color: #fffaf5;">
    <div class="container">
      
      <!-- Section Title -->
      <div class="text-center mb-5" data-aos="fade-up" data-aos-duration="800">
        <div class="d-inline-block px-3 py-1 mb-3" style="background: rgba(245, 158, 11, 0.1); border-radius: 50px;">
          <span style="font-weight: 700; font-size: 0.8rem; color: #f59e0b; text-transform: uppercase;">Antarmuka Aplikasi</span>
        </div>
        <h2 style="font-size: 2.5rem; font-weight: 800; color: #1e1b4b;">Eksplorasi Aplikasi Mobile</h2>
        <p style="font-size: 1.1rem; color: #64748b; max-width: 600px; margin: 15px auto 0;">Tampilan asli dari aplikasi klien berbasis Flutter. Mengutamakan estetika bersih, navigasi mudah, dan performa yang responsif.</p>
      </div>

      <!-- Screenshots Showcase Grid -->
      <div class="row g-4 justify-content-center">
        
        <!-- Screen 1: Login -->
        <div class="col-sm-6 col-md-4 text-center" data-aos="fade-up" data-aos-delay="100" data-aos-duration="800">
          <div class="screenshot-item-wrapper px-3">
            <div class="mobile-frame-mockup" style="display: inline-block; padding: 12px; background: #1e1b4b; border-radius: 36px; box-shadow: 0 20px 40px rgba(0,0,0,0.12); margin-bottom: 20px; transition: transform 0.3s ease;">
              <img src="{{ asset('admin-assets/img/illustrations/Login-Mobie.jpeg') }}" alt="Halaman Login PawPet Mobile" class="img-fluid" style="border-radius: 26px; max-height: 480px; display: block; border: 2px solid rgba(255,255,255,0.1);">
            </div>
            <h5 style="font-weight: 700; color: #1e1b4b; margin-bottom: 5px;">Akses Masuk & Daftar</h5>
            <p style="font-size: 0.9rem; color: #64748b; margin-bottom: 0;">Halaman autentikasi akun pemilik anabul</p>
          </div>
        </div>

        <!-- Screen 2: Home Dashboard -->
        <div class="col-sm-6 col-md-4 text-center" data-aos="fade-up" data-aos-delay="200" data-aos-duration="800">
          <div class="screenshot-item-wrapper px-3">
            <div class="mobile-frame-mockup" style="display: inline-block; padding: 12px; background: #1e1b4b; border-radius: 36px; box-shadow: 0 20px 40px rgba(0,0,0,0.12); margin-bottom: 20px; transition: transform 0.3s ease;">
              <img src="{{ asset('admin-assets/img/illustrations/Home-Mobile.jpeg') }}" alt="Dashboard Utama PawPet Mobile" class="img-fluid" style="border-radius: 26px; max-height: 480px; display: block; border: 2px solid rgba(255,255,255,0.1);">
            </div>
            <h5 style="font-weight: 700; color: #1e1b4b; margin-bottom: 5px;">Dashboard Utama</h5>
            <p style="font-size: 0.9rem; color: #64748b; margin-bottom: 0;">Akses cepat rekam medis dan menu transaksi utama</p>
          </div>
        </div>

        <!-- Screen 3: Booking -->
        <div class="col-sm-6 col-md-4 text-center" data-aos="fade-up" data-aos-delay="300" data-aos-duration="800">
          <div class="screenshot-item-wrapper px-3">
            <div class="mobile-frame-mockup" style="display: inline-block; padding: 12px; background: #1e1b4b; border-radius: 36px; box-shadow: 0 20px 40px rgba(0,0,0,0.12); margin-bottom: 20px; transition: transform 0.3s ease;">
              <img src="{{ asset('admin-assets/img/illustrations/Booking-Mobile.jpeg') }}" alt="Pemesanan Jasa PawPet Mobile" class="img-fluid" style="border-radius: 26px; max-height: 480px; display: block; border: 2px solid rgba(255,255,255,0.1);">
            </div>
            <h5 style="font-weight: 700; color: #1e1b4b; margin-bottom: 5px;">Pemesanan Layanan</h5>
            <p style="font-size: 0.9rem; color: #64748b; margin-bottom: 0;">Pilih tipe paket grooming & jenis kamar penitipan</p>
          </div>
        </div>

      </div>

    </div>
  </section>

  <!-- =========================================================================
     FAQ SECTION
     ========================================================================= -->
  <section id="faq" class="faq-section" style="padding: 100px 0; background-color: #ffffff;">
    <div class="container">
      
      <!-- Section Title -->
      <div class="text-center mb-5" data-aos="fade-up" data-aos-duration="800">
        <div class="d-inline-block px-3 py-1 mb-3" style="background: rgba(245, 158, 11, 0.1); border-radius: 50px;">
          <span style="font-weight: 700; font-size: 0.8rem; color: #f59e0b; text-transform: uppercase;">FAQ</span>
        </div>
        <h2 style="font-size: 2.5rem; font-weight: 800; color: #1e1b4b;">Pertanyaan Umum</h2>
        <p style="font-size: 1.1rem; color: #64748b; max-width: 600px; margin: 15px auto 0;">Jawaban atas pertanyaan-pertanyaan mendasar seputar instalasi, fitur, dan penggunaan aplikasi PawPet.</p>
      </div>

      <!-- Accordion Grid -->
      <div class="row justify-content-center">
        <div class="col-lg-8" data-aos="fade-up" data-aos-duration="800">
          <div class="accordion" id="faqAccordion" style="--bs-accordion-border-color: rgba(0, 0, 0, 0.06); --bs-accordion-btn-focus-box-shadow: none;">
            
            <!-- Accordion Item 1 -->
            <div class="accordion-item mb-3" style="border-radius: 16px; overflow: hidden; border: 1px solid rgba(0,0,0,0.06); box-shadow: 0 4px 15px rgba(0,0,0,0.02);">
              <h2 class="accordion-header" id="headingOne">
                <button class="accordion-button fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne" style="font-family: 'Quicksand', sans-serif; font-size: 1.1rem; padding: 22px; color: #1e1b4b; background-color: #fffbeb;">
                  Bagaimana cara menginstal aplikasi mobile PawPet?
                </button>
              </h2>
              <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                <div class="accordion-body" style="font-size: 1rem; line-height: 1.7; color: #64748b; padding: 25px; background: #fff;">
                  Klik tombol <strong>Download APK Android</strong> yang tertera pada situs ini untuk mengunduh berkas instalasi (.apk) secara aman dari cloud server Mega.nz. Setelah proses unduh selesai, buka berkas tersebut di perangkat Android Anda, berikan izin untuk melakukan instalasi dari sumber tidak dikenal di menu pengaturan ponsel, kemudian ikuti instruksi singkat di layar hingga selesai.
                </div>
              </div>
            </div>

            <!-- Accordion Item 2 -->
            <div class="accordion-item mb-3" style="border-radius: 16px; overflow: hidden; border: 1px solid rgba(0,0,0,0.06); box-shadow: 0 4px 15px rgba(0,0,0,0.02);">
              <h2 class="accordion-header" id="headingTwo">
                <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo" style="font-family: 'Quicksand', sans-serif; font-size: 1.1rem; padding: 22px; color: #1e1b4b; background-color: #fff;">
                  Apakah rekam medis anabul tersinkronisasi secara real-time?
                </button>
              </h2>
              <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                <div class="accordion-body" style="font-size: 1rem; line-height: 1.7; color: #64748b; padding: 25px; background: #fff;">
                  Ya, sistem integrasi database PawPet memastikan setiap data medis, resep obat, jenis penanganan, maupun catatan pasca-grooming yang diinput oleh dokter hewan atau staf kasir di <strong>Web Dashboard Admin</strong> langsung terupdate secara real-time dan langsung tampil di halaman rekam medis <strong>Aplikasi Mobile Pemilik Anabul</strong>.
                </div>
              </div>
            </div>

            <!-- Accordion Item 3 -->
            <div class="accordion-item mb-3" style="border-radius: 16px; overflow: hidden; border: 1px solid rgba(0,0,0,0.06); box-shadow: 0 4px 15px rgba(0,0,0,0.02);">
              <h2 class="accordion-header" id="headingThree">
                <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree" style="font-family: 'Quicksand', sans-serif; font-size: 1.1rem; padding: 22px; color: #1e1b4b; background-color: #fff;">
                  Layanan apa saja yang dapat dipesan melalui aplikasi mobile?
                </button>
              </h2>
              <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                <div class="accordion-body" style="font-size: 1rem; line-height: 1.7; color: #64748b; padding: 25px; background: #fff;">
                  Anda dapat memesan layanan <strong>Grooming</strong> (dengan memilih jenis paket spa, kuku, atau potong bulu), layanan <strong>Boarding</strong> (pemesanan kamar penginapan penitipan hewan sesuai ketersediaan), serta penjadwalan janji temu konsultasi <strong>Dokter Hewan</strong> berdasarkan tanggal dan jam kerja dokter yang bersangkutan.
                </div>
              </div>
            </div>

            <!-- Accordion Item 4 -->
            <div class="accordion-item mb-3" style="border-radius: 16px; overflow: hidden; border: 1px solid rgba(0,0,0,0.06); box-shadow: 0 4px 15px rgba(0,0,0,0.02);">
              <h2 class="accordion-header" id="headingFour">
                <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour" style="font-family: 'Quicksand', sans-serif; font-size: 1.1rem; padding: 22px; color: #1e1b4b; background-color: #fff;">
                  Bagaimana sistem pembayaran untuk pemesanan jasa?
                </button>
              </h2>
              <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#faqAccordion">
                <div class="accordion-body" style="font-size: 1rem; line-height: 1.7; color: #64748b; padding: 25px; background: #fff;">
                  PawPet terintegrasi dengan Payment Gateway Midtrans. Ketika Anda melakukan check-out transaksi booking layanan di aplikasi, sistem akan membuat invoice pembayaran otomatis. Anda dapat langsung membayar menggunakan Virtual Account bank (BNI, BRI, Mandiri, dll.), dompet digital e-wallet (GoPay, ShopeePay), atau scan QRIS secara aman dan praktis.
                </div>
              </div>
            </div>

            <!-- Accordion Item 5 -->
            <div class="accordion-item mb-3" style="border-radius: 16px; overflow: hidden; border: 1px solid rgba(0,0,0,0.06); box-shadow: 0 4px 15px rgba(0,0,0,0.02);">
              <h2 class="accordion-header" id="headingFive">
                <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive" style="font-family: 'Quicksand', sans-serif; font-size: 1.1rem; padding: 22px; color: #1e1b4b; background-color: #fff;">
                  Apakah dashboard admin web dapat diakses oleh umum?
                </button>
              </h2>
              <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#faqAccordion">
                <div class="accordion-body" style="font-size: 1rem; line-height: 1.7; color: #64748b; padding: 25px; background: #fff;">
                  Tidak. Web Dashboard Admin dikhususkan secara eksklusif bagi administrator klinik, dokter hewan medis, dan staf kasir terdaftar. Hak akses login staf dibuat dan dikelola secara aman oleh administrator klinik dari panel utama sistem guna melindungi kerahasiaan data medis anabul.
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>

    </div>
  </section>

  <!-- =========================================================================
     DOWNLOAD CTA SECTION (BOTTOM CARD)
     ========================================================================= -->
  <section id="download" class="download-section" style="padding: 100px 0; background: radial-gradient(circle at 10% 90%, #fffbeb 0%, #ffffff 100%);">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-10" data-aos="zoom-in" data-aos-duration="800">
          <div class="download-cta-card text-center text-white" style="padding: 60px 40px; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border-radius: 30px; box-shadow: 0 20px 50px rgba(245, 158, 11, 0.35); position: relative; overflow: hidden;">
            <!-- Decorative circle shapes -->
            <div style="position: absolute; top: -50px; left: -50px; width: 180px; height: 180px; background: rgba(255,255,255,0.06); border-radius: 50%;"></div>
            <div style="position: absolute; bottom: -80px; right: -80px; width: 220px; height: 220px; background: rgba(255,255,255,0.06); border-radius: 50%;"></div>

            <!-- Content -->
            <h2 style="font-size: clamp(1.8rem, 4vw, 2.8rem); font-weight: 800; margin-bottom: 20px; position: relative;">Siap Memberikan yang Terbaik untuk Anabul?</h2>
            <p style="font-size: 1.15rem; opacity: 0.9; max-width: 650px; margin: 0 auto 35px; line-height: 1.7; position: relative;">
              Unduh aplikasi mobile PawPet Android sekarang juga dan rasakan kemudahan mengelola semua riwayat kesehatan dan pesanan perawatan hewan peliharaan Anda secara modern dan terintegrasi.
            </p>

            <div class="d-flex justify-content-center gap-3 flex-wrap position-relative">
              <a href="https://mega.nz/file/ex5FRTZa#B8tYmaDeDlQzp6C2-qxD4dvOUUBHK9hsCt244u83gvI" target="_blank" class="btn btn-light px-4 py-3" style="border-radius: 50px; font-weight: 700; font-size: 1.1rem; color: #d97706; box-shadow: 0 4px 15px rgba(0,0,0,0.1); border: none; display: inline-flex; align-items: center; transition: all 0.3s ease;">
                <i class="bi bi-android2 me-2" style="font-size: 1.3rem;"></i> Download APK Android
              </a>
            </div>

            <!-- Guide Steps -->
            <div class="row g-4 mt-5 text-start justify-content-center position-relative" style="border-top: 1px solid rgba(255,255,255,0.15); padding-top: 40px;">
              <div class="col-md-4">
                <div class="d-flex align-items-start gap-3">
                  <div class="step-num" style="width: 32px; height: 32px; border-radius: 50%; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.95rem; flex-shrink:0;">1</div>
                  <p style="font-size: 0.92rem; line-height: 1.5; margin-bottom: 0;">Unduh berkas instalasi APK yang disediakan melalui tautan aman cloud Mega.nz.</p>
                </div>
              </div>
              <div class="col-md-4">
                <div class="d-flex align-items-start gap-3">
                  <div class="step-num" style="width: 32px; height: 32px; border-radius: 50%; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.95rem; flex-shrink:0;">2</div>
                  <p style="font-size: 0.92rem; line-height: 1.5; margin-bottom: 0;">Aktifkan persetujuan pemasangan aplikasi dari 'Sumber Tidak Dikenal' di menu pengaturan.</p>
                </div>
              </div>
              <div class="col-md-4">
                <div class="d-flex align-items-start gap-3">
                  <div class="step-num" style="width: 32px; height: 32px; border-radius: 50%; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.95rem; flex-shrink:0;">3</div>
                  <p style="font-size: 0.92rem; line-height: 1.5; margin-bottom: 0;">Buka aplikasi, daftar atau masuk dengan akun Anda, dan daftarkan profil anabul kesayangan.</p>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </section>

</main>

<style>
  /* --- Premium Animations & Hover States --- */
  .btn-hero-download:hover {
    background: #e08a00 !important;
    transform: translateY(-3px);
    box-shadow: 0 12px 30px rgba(245, 158, 11, 0.45) !important;
  }
  .btn-hero-download:active {
    transform: translateY(-1px);
  }
  .hero-illustration-img:hover {
    transform: translateY(-6px) scale(1.01);
    box-shadow: 0 25px 50px rgba(0,0,0,0.1) !important;
  }
  .feature-card-clean:hover {
    transform: translateY(-8px);
    border-color: rgba(245, 158, 11, 0.15) !important;
    box-shadow: 0 15px 35px rgba(245, 158, 11, 0.05) !important;
  }
  .feature-card-clean:hover .icon-box-feature {
    background: #f59e0b !important;
    color: #fff !important;
    transform: scale(1.05);
  }
  .mobile-frame-mockup:hover {
    transform: translateY(-8px) scale(1.01);
    box-shadow: 0 25px 45px rgba(0,0,0,0.18) !important;
  }
  .accordion-button:not(.collapsed) {
    box-shadow: none !important;
  }
  .download-cta-card .btn:hover {
    transform: translateY(-3px);
    background: #ffffff !important;
    color: #b45309 !important;
    box-shadow: 0 10px 25px rgba(0,0,0,0.15) !important;
  }
</style>
@endsection