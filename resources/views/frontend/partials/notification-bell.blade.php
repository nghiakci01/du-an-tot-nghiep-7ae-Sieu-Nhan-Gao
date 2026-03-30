{{-- Notification Bell Widget --}}
@php
    $unreadCount = auth()->check() ? auth()->user()->unreadNotifications->count() : 0;
    $notifications = auth()->check() ? auth()->user()->notifications()->take(5)->get() : [];
@endphp

<div class="notification_link" style="position: relative; margin-right: 20px; display: flex; align-items: center; height: 100%;">
    <a href="javascript:void(0)" id="notification-toggle" style="font-size: 22px; color: #333; position: relative; display: flex; align-items: center; height: 100%;">
        <i class="ion-android-notifications-none"></i>
        @auth
            @if($unreadCount > 0)
                <span class="notification-badge" style="position: absolute; top: 0px; right: -10px; background: #ef233c; color: white; border-radius: 50%; padding: 2px 5px; font-size: 10px; font-weight: bold; line-height: 1;">{{ $unreadCount > 99 ? '99+' : $unreadCount }}</span>
            @endif
        @endauth
    </a>
    
    <!-- dropdown -->
    <div class="notification_dropdown" style="display: none; position: absolute; right: -10px; top: 100%; width: 320px; background: white; box-shadow: 0 5px 20px rgba(0,0,0,0.15); border-radius: 8px; z-index: 9999; border: 1px solid #f0f0f0;">
        <div class="p-3 border-bottom d-flex justify-content-between align-items-center" style="background: #f8f9fa; border-radius: 8px 8px 0 0;">
            <strong style="font-size: 14px; margin: 0;">Thông báo</strong>
            @auth
                @if($unreadCount > 0)
                    <a href="javascript:void(0)" class="mark-all-read" style="font-size: 12px; color: #ef233c; text-decoration: none;">Đánh dấu đã đọc</a>
                @endif
            @endauth
        </div>
        
        <div class="notification-list" style="max-height: 350px; overflow-y: auto;">
            @auth
                @forelse($notifications as $notify)
                    <a href="{{ isset($notify->data['url']) ? $notify->data['url'] : 'javascript:void(0)' }}" 
                       class="notify-item {{ is_null($notify->read_at) ? 'unread' : '' }}" 
                       data-id="{{ $notify->id }}"
                       style="display: block; padding: 12px 15px; border-bottom: 1px solid #f5f5f5; text-decoration: none; transition: background 0.2s; position: relative; {{ is_null($notify->read_at) ? 'background-color: #f4f8ff;' : 'background-color: white;' }}">
                        
                        @if(is_null($notify->read_at))
                            <span class="unread-dot" style="position: absolute; right: 15px; top: 20px; width: 8px; height: 8px; background: #ef233c; border-radius: 50%;"></span>
                        @endif
                        
                        <div style="font-size: 13px; color: #333; padding-right: 15px; line-height: 1.4;">
                            {{ $notify->data['message'] ?? 'Bạn có thông báo mới' }}
                        </div>
                        <small style="font-size: 11px; color: #888; display: block; margin-top: 5px;">
                            <i class="ion-clock"></i> {{ $notify->created_at->diffForHumans() }}
                        </small>
                    </a>
                @empty
                    <div class="p-4 text-center text-muted" style="font-size: 13px;">
                        Chưa có thông báo nào.
                    </div>
                @endforelse
            @else
                <div class="p-4 text-center text-muted" style="font-size: 13px;">
                    Vui lòng <a href="{{ route('login') }}" style="color: #ef233c; text-decoration: underline;">đăng nhập</a> để xem thông báo.
                </div>
            @endauth
        </div>
        
        @auth
        <div class="p-2 text-center border-top">
            <a href="{{ route('account.index') }}#notifications" style="font-size: 13px; color: #333; text-decoration: none; font-weight: 500;">Xem tất cả</a>
        </div>
        @endauth
    </div>
</div>

<style>
.notification_dropdown .notify-item:hover { background-color: #f9f9f9 !important; }
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('notification-toggle');
    const dropdown = document.querySelector('.notification_dropdown');
    const badge = document.querySelector('.notification-badge');
    const markAllBtn = document.querySelector('.mark-all-read');
    
    if(toggleBtn && dropdown) {
        toggleBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
        });

        document.addEventListener('click', function(e) {
            if(!dropdown.contains(e.target) && e.target !== toggleBtn) {
                dropdown.style.display = 'none';
            }
        });
    }

    // Ajax Mark As Read single
    document.querySelectorAll('.notify-item.unread').forEach(function(item) {
        item.addEventListener('click', function(e) {
            // Let the link navigation happen, but fire an ajax request first
            const notifyId = this.dataset.id;
            const url = '{{ url("/notifications") }}/' + notifyId + '/mark-as-read';
            
            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }).catch(console.error);
        });
    });

    // Mark All As Read
    if(markAllBtn) {
        markAllBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            fetch('{{ route("notifications.mark_all_read") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }).then(r => r.json()).then(data => {
                if(data.status === 'success') {
                    document.querySelectorAll('.notify-item.unread').forEach(item => {
                        item.classList.remove('unread');
                        item.style.backgroundColor = 'white';
                        const dot = item.querySelector('.unread-dot');
                        if(dot) dot.remove();
                    });
                    if(badge) badge.style.display = 'none';
                    this.style.display = 'none';
                }
            }).catch(console.error);
        });
    }
});
</script>
@endpush
