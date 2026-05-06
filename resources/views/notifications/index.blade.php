@extends('layouts.admin')
@section('title', 'Semua Notifikasi')

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Riwayat Notifikasi</h5>
        <button class="btn btn-sm btn-outline-primary" onclick="window.markAllNotificationsAsRead(); setTimeout(() => location.reload(), 500);">
            Tandai Semua Dibaca
        </button>
      </div>
      <div class="card-body p-0">
        <div class="list-group list-group-flush">
          @forelse($notifications as $notif)
            @php
                $icon = 'bx-info-circle';
                $type = $notif->data['type'] ?? 'primary';
                if($type == 'success') $icon = 'bx-check-circle';
                if($type == 'warning') $icon = 'bx-error';
                if($type == 'danger') $icon = 'bx-x-circle';
                $url = $notif->data['url'] ?? '#';
            @endphp
            <a href="{{ $url !== '#' ? $url : 'javascript:void(0)' }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-start p-4 {{ is_null($notif->read_at) ? 'bg-lighter border-start border-3 border-primary' : '' }}" @if(is_null($notif->read_at)) onclick="window.markNotificationAsRead('{{ $notif->id }}')" @endif>
              <div class="d-flex align-items-start w-100">
                <div class="flex-shrink-0 me-3 mt-1">
                    <span class="avatar-initial rounded-circle bg-label-{{ $type }} p-2"><i class="bx {{ $icon }} fs-4"></i></span>
                </div>
                <div class="flex-grow-1 w-100">
                  <div class="d-flex w-100 justify-content-between">
                    <h6 class="mb-1 fw-bold text-dark">{{ $notif->data['title'] ?? 'Info' }}</h6>
                    <small class="text-muted"><i class="bx bx-time-five me-1"></i>{{ $notif->created_at->diffForHumans() }}</small>
                  </div>
                  <p class="mb-0 text-secondary">{{ $notif->data['message'] ?? '' }}</p>
                </div>
              </div>
            </a>
          @empty
            <div class="text-center py-5">
              <i class="bx bx-bell-off fs-1 text-muted mb-3"></i>
              <h5>Tidak ada notifikasi</h5>
              <p class="text-muted">Anda belum menerima notifikasi apapun sejauh ini.</p>
            </div>
          @endforelse
        </div>
      </div>
      @if($notifications->hasPages())
      <div class="card-footer border-top pt-3 pb-0 d-flex justify-content-center">
        {{ $notifications->links() }}
      </div>
      @endif
    </div>
  </div>
</div>
@endsection
