@extends('layouts.admin')
@section('title', 'Semua Notifikasi')

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Riwayat Notifikasi</h5>
        <button class="btn btn-primary" onclick="window.markAllNotificationsAsRead(); setTimeout(() => location.reload(), 500);">
            <i class="bx bx-check-double me-1"></i> Tandai Semua Dibaca
        </button>
      </div>
      <div class="card-body">
        <div class="table-responsive text-nowrap">
          <table class="table table-hover">
            <thead>
              <tr>
                <th>No</th>
                <th>Judul</th>
                <th>Pesan</th>
                <th>Status</th>
                <th>Waktu</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody class="table-border-bottom-0">
              @forelse($notifications as $notif)
                @php
                    $icon = 'bx-info-circle';
                    $type = $notif->data['type'] ?? 'primary';
                    if($type == 'success') $icon = 'bx-check-circle';
                    if($type == 'warning') $icon = 'bx-error';
                    if($type == 'danger') $icon = 'bx-x-circle';
                    $url = $notif->data['url'] ?? '#';
                    if ($url !== '#' && filter_var($url, FILTER_VALIDATE_URL)) {
                        $parsed = parse_url($url);
                        $path = $parsed['path'] ?? '';
                        $query = isset($parsed['query']) ? '?' . $parsed['query'] : '';
                        $url = url($path . $query);
                    }
                    $isUnread = is_null($notif->read_at);
                @endphp
                <tr class="{{ $isUnread ? 'table-active' : '' }}">
                  <td>{{ $loop->iteration + $notifications->firstItem() - 1 }}</td>
                  <td>
                    <span class="text-{{ $type }} me-1"><i class="bx {{ $icon }}"></i></span>
                    <strong>{{ $notif->data['title'] ?? 'Info' }}</strong>
                  </td>
                  <td>
                      <span class="d-inline-block text-truncate" style="max-width: 300px;">
                          {{ $notif->data['message'] ?? '' }}
                      </span>
                  </td>
                  <td>
                    @if($isUnread)
                      <span class="badge bg-label-primary">Baru</span>
                    @else
                      <span class="badge bg-label-secondary">Dibaca</span>
                    @endif
                  </td>
                  <td>
                      {{ $notif->created_at->format('d/m/Y H:i') }}
                      <br>
                      <small class="text-muted">{{ $notif->created_at->diffForHumans() }}</small>
                  </td>
                  <td>
                    @if($url !== '#')
                      <a href="{{ $url }}" class="btn btn-sm btn-icon btn-info" title="Lihat Detail" @if($isUnread) onclick="window.markNotificationAsRead('{{ $notif->id }}')" @endif>
                        <i class="bx bx-link-external"></i>
                      </a>
                    @endif
                    @if($isUnread)
                      <button type="button" class="btn btn-sm btn-icon btn-success" title="Tandai Dibaca" onclick="window.markNotificationAsRead('{{ $notif->id }}'); setTimeout(() => location.reload(), 300);">
                        <i class="bx bx-check"></i>
                      </button>
                    @endif
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="6" class="text-center text-muted py-4">
                      <i class="bx bx-bell-off fs-1 text-muted mb-2"></i>
                      <p class="mb-0">Belum ada data notifikasi.</p>
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        @if($notifications->hasPages())
          <div class="mt-4">
            {{ $notifications->links('pagination::bootstrap-5') }}
          </div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection
