<nav class="layout-navbar container-xxl navbar-detached navbar navbar-expand-xl align-items-center bg-navbar-theme" id="layout-navbar">
  <div class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0 d-xl-none">
    <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
      <i class="icon-base bx bx-menu icon-md"></i>
    </a>
  </div>

  <div class="navbar-nav-right d-flex align-items-center justify-content-end" id="navbar-collapse">
    <!-- Search -->
    <div class="navbar-nav align-items-center me-auto">
      <div class="nav-item d-flex align-items-center">
        <span class="w-px-22 h-px-22"><i class="icon-base bx bx-search icon-md"></i></span>
        <input type="text" class="form-control border-0 shadow-none ps-1 ps-sm-2 d-md-block d-none" placeholder="Cari Hewan, Produk, atau Transaksi..." aria-label="Cari Hewan, Produk, atau Transaksi..." />
      </div>
    </div>

    <ul class="navbar-nav flex-row align-items-center ms-md-auto">
      <!-- Notification -->
      <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-3 me-xl-1">
        <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
          <i class="icon-base bx bx-bell icon-md"></i>
          @if(auth()->user()->unreadNotifications->count() > 0)
            <span class="badge bg-danger rounded-pill badge-notifications">{{ auth()->user()->unreadNotifications->count() }}</span>
          @endif
        </a>
        <ul class="dropdown-menu dropdown-menu-end py-0">
          <li class="dropdown-menu-header border-bottom">
            <div class="dropdown-header d-flex align-items-center py-3">
              <h5 class="text-body mb-0 me-auto">Notifikasi</h5>
              <a href="javascript:void(0)" class="dropdown-notifications-all text-body" data-bs-toggle="tooltip" data-bs-placement="top" title="Tandai semua sudah dibaca" onclick="markAllNotificationsAsRead()"><i class="icon-base bx fs-4 bx-envelope-open"></i></a>
            </div>
          </li>
          <li class="dropdown-notifications-list scrollable-container">
            <ul class="list-group list-group-flush" id="notification-list">
              @forelse(auth()->user()->unreadNotifications as $notification)
                <li class="list-group-item list-group-item-action dropdown-notifications-item" id="notif-{{ $notification->id }}">
                  <div class="d-flex">
                    <div class="flex-shrink-0 me-3">
                      <div class="avatar">
                        <span class="avatar-initial rounded-circle bg-label-{{ $notification->data['type'] ?? 'primary' }}"><i class="bx bx-info-circle"></i></span>
                      </div>
                    </div>
                    <div class="flex-grow-1">
                      <h6 class="mb-1">{{ $notification->data['title'] ?? 'Info' }}</h6>
                      <p class="mb-0">{{ $notification->data['message'] ?? '' }}</p>
                      <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                    </div>
                    <div class="flex-shrink-0 dropdown-notifications-actions">
                      <a href="javascript:void(0)" class="dropdown-notifications-read" onclick="markNotificationAsRead('{{ $notification->id }}')"><span class="badge badge-dot"></span></a>
                      <a href="javascript:void(0)" class="dropdown-notifications-archive" onclick="markNotificationAsRead('{{ $notification->id }}')"><span class="bx bx-x"></span></a>
                    </div>
                  </div>
                </li>
              @empty
                <li class="list-group-item text-center text-muted py-4" id="empty-notif">
                  Tidak ada notifikasi baru.
                </li>
              @endforelse
            </ul>
          </li>
          <li class="dropdown-menu-footer border-top p-3">
            <button class="btn btn-primary text-uppercase w-100" disabled>Lihat Semua Notifikasi</button>
          </li>
        </ul>
      </li>

      <!-- User -->
      <li class="nav-item navbar-dropdown dropdown-user dropdown">
        <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);" data-bs-toggle="dropdown">
          <div class="avatar avatar-online">
            <img src="{{ asset('admin-assets/img/avatars/1.png') }}" alt class="w-px-40 h-auto rounded-circle" />
          </div>
        </a>
        <ul class="dropdown-menu dropdown-menu-end">
          <li>
            <a class="dropdown-item" href="#">
              <div class="d-flex">
                <div class="flex-shrink-0 me-3">
                  <div class="avatar avatar-online">
                    <img src="{{ asset('admin-assets/img/avatars/1.png') }}" alt class="w-px-40 h-auto rounded-circle" />
                  </div>
                </div>
                <div class="flex-grow-1">
                  <h6 class="mb-0">{{ Auth::user()->nama ?? 'Admin' }}</h6>
                  <small class="text-body-secondary">{{ ucfirst(Auth::user()->role ?? 'admin') }}</small>
                </div>
              </div>
            </a>
          </li>
          <li><div class="dropdown-divider my-1"></div></li>
          <li>
            <a class="dropdown-item" href="{{ route('profile.edit') }}">
              <i class="icon-base bx bx-user icon-md me-3"></i><span>Profil Saya</span>
            </a>
          </li>
          <li><div class="dropdown-divider my-1"></div></li>
          <li>
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">
                <i class="icon-base bx bx-power-off icon-md me-3"></i><span>Keluar</span>
              </a>
            </form>
          </li>
        </ul>
      </li>
    </ul>
  </div>
</nav>
