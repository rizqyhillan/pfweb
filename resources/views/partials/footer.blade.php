<footer id="footer" class="footer position-relative" style="background-color: #1e1b4b; color: rgba(255, 255, 255, 0.7); padding: 80px 0 30px; font-family: 'Quicksand', sans-serif;">

  <div class="container">
    <div class="row g-5 align-items-start">

      <!-- Column 1: Brand & Contact -->
      <div class="col-lg-5">
        <div class="brand-section">
          <a href="{{ route('home') }}" class="logo d-flex align-items-center mb-4" style="text-decoration: none;">
            <img src="{{ asset('assets/pawpet/logo/PawPet Logo New.jpg') }}" alt="PawPet Logo" style="max-height: 40px; border-radius: 8px; margin-right: 12px;">
            <span style="font-weight: 700; font-size: 1.5rem; color: #f59e0b;">PawPet</span>
          </a>
          <p style="font-size: 0.95rem; line-height: 1.7; color: rgba(255, 255, 255, 0.65); margin-bottom: 30px;">
            Ekosistem terintegrasi untuk segala kebutuhan anabul kesayangan Anda. Memadukan kemudahan aplikasi mobile untuk pemilik anabul dengan keandalan Laravel web dashboard untuk manajemen klinik dan dokter hewan secara real-time.
          </p>

          <div class="contact-info" style="font-size: 0.9rem; display: flex; flex-column: gap-3; flex-direction: column;">
            <div class="d-flex align-items-center gap-3 mb-2">
              <i class="bi bi-geo-alt-fill" style="color: #f59e0b; font-size: 1.1rem;"></i>
              <span>Jl. Raya Pet Care No. 123, Jakarta</span>
            </div>
            <div class="d-flex align-items-center gap-3 mb-2">
              <i class="bi bi-telephone-fill" style="color: #f59e0b; font-size: 1.1rem;"></i>
              <span>+62 811 2233 4455</span>
            </div>
            <div class="d-flex align-items-center gap-3">
              <i class="bi bi-envelope-fill" style="color: #f59e0b; font-size: 1.1rem;"></i>
              <span>hello@pawpet.id</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Column 2: Navigation Links -->
      <div class="col-6 col-lg-3 offset-lg-1">
        <div class="nav-column">
          <h5 style="font-weight: 700; color: #fff; font-size: 1.1rem; margin-bottom: 25px; border-left: 3px solid #f59e0b; padding-left: 10px;">Navigasi</h5>
          <nav style="display: flex; flex-direction: column; gap: 12px;">
            <a href="#hero" class="footer-link" style="color: rgba(255, 255, 255, 0.7); text-decoration: none; font-size: 0.95rem; transition: color 0.2s;">Home</a>
            <a href="#fitur" class="footer-link" style="color: rgba(255, 255, 255, 0.7); text-decoration: none; font-size: 0.95rem; transition: color 0.2s;">Fitur Layanan</a>
            <a href="#showcase" class="footer-link" style="color: rgba(255, 255, 255, 0.7); text-decoration: none; font-size: 0.95rem; transition: color 0.2s;">Tampilan Aplikasi</a>
            <a href="#faq" class="footer-link" style="color: rgba(255, 255, 255, 0.7); text-decoration: none; font-size: 0.95rem; transition: color 0.2s;">FAQ</a>
            <a href="#download" class="footer-link" style="color: rgba(255, 255, 255, 0.7); text-decoration: none; font-size: 0.95rem; transition: color 0.2s;">Download APK</a>
          </nav>
        </div>
      </div>

      <!-- Column 3: Social Media Links -->
      <div class="col-6 col-lg-3">
        <div class="nav-column">
          <h5 style="font-weight: 700; color: #fff; font-size: 1.1rem; margin-bottom: 25px; border-left: 3px solid #f59e0b; padding-left: 10px;">Ikuti Kami</h5>
          <nav style="display: flex; flex-direction: column; gap: 12px;">
            <a href="https://instagram.com" target="_blank" rel="noopener" class="footer-link" style="color: rgba(255, 255, 255, 0.7); text-decoration: none; font-size: 0.95rem; transition: color 0.2s;"><i class="bi bi-instagram me-2"></i>Instagram</a>
            <a href="https://facebook.com" target="_blank" rel="noopener" class="footer-link" style="color: rgba(255, 255, 255, 0.7); text-decoration: none; font-size: 0.95rem; transition: color 0.2s;"><i class="bi bi-facebook me-2"></i>Facebook</a>
            <a href="https://tiktok.com" target="_blank" rel="noopener" class="footer-link" style="color: rgba(255, 255, 255, 0.7); text-decoration: none; font-size: 0.95rem; transition: color 0.2s;"><i class="bi bi-tiktok me-2"></i>TikTok</a>
            <a href="https://twitter.com" target="_blank" rel="noopener" class="footer-link" style="color: rgba(255, 255, 255, 0.7); text-decoration: none; font-size: 0.95rem; transition: color 0.2s;"><i class="bi bi-twitter me-2"></i>Twitter</a>
          </nav>
        </div>
      </div>

    </div>
  </div>

  <!-- Footer Bottom Section -->
  <div class="footer-bottom mt-5 pt-4" style="border-top: 1px solid rgba(255, 255, 255, 0.08); font-size: 0.85rem; color: rgba(255, 255, 255, 0.5);">
    <div class="container">
      <div class="row align-items-center">
        
        <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
          <p class="m-0">© {{ date('Y') }} PawPet Ecosystem. All rights reserved.</p>
        </div>

        <div class="col-md-6 text-center text-md-end">
          <div class="d-flex gap-4 justify-content-center justify-content-md-end">
            <span style="cursor: default;">Privacy Policy</span>
            <span style="cursor: default;">Terms of Service</span>
            <span style="cursor: default;">Cookie Policy</span>
          </div>
        </div>

      </div>
    </div>
  </div>

</footer>

<style>
  .footer-link:hover {
    color: #f59e0b !important;
    padding-left: 3px !important;
    transition: all 0.2s;
  }
</style>