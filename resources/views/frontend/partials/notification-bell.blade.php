{{-- Notification Bell Widget --}}
@php
    $unreadCount = auth()->check() ? auth()->user()->unreadNotifications->count() : 0;
    $notifications = auth()->check() ? auth()->user()->notifications()->take(5)->get() : [];
@endphp

<div class="notification_link" style="position: relative; margin-right: 15px; display: inline-block;">
    <a href="javascript:void(0)" class="notification-toggle" style="font-size: 22px; color: #333; position: relative; display: inline-block; vertical-align: middle;">
        <i class="ion-android-notifications-none"></i>
        @auth
            @if($unreadCount > 0)
                <span class="notification-badge" style="position: absolute; top: -2px; right: -8px; background: #ef233c; color: white; border-radius: 50%; padding: 2px 5px; font-size: 10px; font-weight: bold; line-height: 1;">{{ $unreadCount > 99 ? '99+' : $unreadCount }}</span>
            @endif
        @endauth
    </a>
    
    <!-- dropdown -->
    <div class="notification_dropdown" style="display: none; position: absolute; right: -10px; top: 100%; margin-top: 10px; width: 320px; background: white; box-shadow: 0 5px 20px rgba(0,0,0,0.15); border-radius: 8px; z-index: 9999; border: 1px solid #f0f0f0;">
        <div class="p-3 border-bottom d-flex justify-content-between align-items-center" style="background: #f8f9fa; border-radius: 8px 8px 0 0;">
            <strong style="font-size: 14px; margin: 0;">Thông báo</strong>
            @auth
                @if($unreadCount > 0)
                    <a href="javascript:void(0)" class="mark-all-read" style="font-size: 12px; color: #ef233c; text-decoration: none;">Đánh dấu đã đọc</a>
                @endif
            @endauth
        </div>
        
        <div class="notification-list" style="max-height: 350px; overflow-y: auto;">
            @forelse($notifications as $notify)
                <a href="{{ isset($notify->data['url']) ? $notify->data['url'] : (isset($notify->data['link']) ? $notify->data['link'] : 'javascript:void(0)') }}" 
                   class="notify-item {{ is_null($notify->read_at) ? 'unread' : '' }}" 
                   data-id="{{ $notify->id }}"
                   style="display: block; padding: 12px 15px; border-bottom: 1px solid #f5f5f5; text-decoration: none; transition: background 0.2s; position: relative; {{ is_null($notify->read_at) ? 'background-color: #f4f8ff;' : 'background-color: white;' }}">
                    
                    @if(is_null($notify->read_at))
                        <span class="unread-dot" style="position: absolute; right: 15px; top: 20px; width: 8px; height: 8px; background: #ef233c; border-radius: 50%;"></span>
                    @endif
                    
                    <div style="font-size: 13px; color: #333; padding-right: 15px; line-height: 1.4;">
                        {{ $notify->data['message'] ?? 'Bạn có thông báo mới' }}
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
/* Ensure the bell area doesn't break layout */
.notification_link { display: inline-flex !important; align-items: center; }
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle all notification toggles (for desktop, sticky, and mobile)
    document.querySelectorAll('.notification-toggle').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const wrapper = this.closest('.notification_link');
            const dropdown = wrapper.querySelector('.notification_dropdown');
            
            // Close other dropdowns first
            document.querySelectorAll('.notification_dropdown').forEach(d => {
                if(d !== dropdown) d.style.display = 'none';
            });
            
            dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
        });
    });

    // Close when clicking outside
    document.addEventListener('click', function(e) {
        document.querySelectorAll('.notification_dropdown').forEach(dropdown => {
            if(!dropdown.contains(e.target) && !e.target.closest('.notification-toggle')) {
                dropdown.style.display = 'none';
            }
        });
    });

    // Ajax Mark As Read single
    document.querySelectorAll('.notify-item.unread').forEach(function(item) {
        item.addEventListener('click', function(e) {
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
    document.querySelectorAll('.mark-all-read').forEach(function(markAllBtn) {
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
                    // Update all badges and items across all bell instances
                    document.querySelectorAll('.notify-item.unread').forEach(item => {
                        item.classList.remove('unread');
                        item.style.backgroundColor = 'white';
                        const dot = item.querySelector('.unread-dot');
                        if(dot) dot.remove();
                    });
                    document.querySelectorAll('.notification-badge').forEach(badge => {
                        badge.style.display = 'none';
                    });
                    document.querySelectorAll('.mark-all-read').forEach(btn => {
                        btn.style.display = 'none';
                    });
                }
            }).catch(console.error);
        });
    });
});
</script>
@endpush
