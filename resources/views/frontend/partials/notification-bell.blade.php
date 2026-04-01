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
            @auth
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

    // Auto Refresh Notifications every 30 seconds (Real-time imitation)
    @auth
    var _notifPollTimer = setInterval(function() {
        fetch('{{ route("notifications.list") }}', { headers: { 'Accept': 'application/json' } })
            .then(res => {
                // Session expired — stop polling and hide badges
                if (res.status === 401) {
                    clearInterval(_notifPollTimer);
                    document.querySelectorAll('.notification-badge').forEach(b => b.style.display = 'none');
                    return null;
                }
                return res.json();
            })
            .then(data => {
                if (!data) return; // Stopped due to 401

                const unreadCount = data.unread_count || 0;
                const notifs = data.notifications || [];

                // Update badges
                document.querySelectorAll('.notification-toggle').forEach(toggle => {
                    let badge = toggle.querySelector('.notification-badge');
                    if (unreadCount > 0) {
                        if (!badge) {
                            badge = document.createElement('span');
                            badge.className = 'notification-badge';
                            badge.style.cssText = 'position: absolute; top: -2px; right: -8px; background: #ef233c; color: white; border-radius: 50%; padding: 2px 5px; font-size: 10px; font-weight: bold; line-height: 1;';
                            toggle.appendChild(badge);
                        }
                        badge.style.display = 'inline-block';
                        badge.innerText = unreadCount > 99 ? '99+' : unreadCount;
                    } else {
                        if (badge) badge.style.display = 'none';
                    }
                });

                // Update mark all as read visibility
                document.querySelectorAll('.mark-all-read').forEach(el => {
                    if (unreadCount > 0) {
                        el.style.display = 'inline';
                    } else {
                        el.style.display = 'none';
                    }
                });

                // Re-render list
                let html = '';
                if (notifs.length > 0) {
                    notifs.forEach(n => {
                        const link = (n.data && n.data.url) ? n.data.url : ((n.data && n.data.link) ? n.data.link : 'javascript:void(0)');
                        const message = (n.data && n.data.message) ? n.data.message : 'Bạn có thông báo mới';
                        const isUnread = n.read_at === null;
                        const bgStyle = isUnread ? 'background-color: #f4f8ff;' : 'background-color: white;';
                        const unreadClass = isUnread ? 'unread' : '';
                        
                        html += `
                            <a href="${link}" class="notify-item ${unreadClass}" data-id="${n.id}"
                               style="display: block; padding: 12px 15px; border-bottom: 1px solid #f5f5f5; text-decoration: none; transition: background 0.2s; position: relative; ${bgStyle}">
                                ${isUnread ? '<span class="unread-dot" style="position: absolute; right: 15px; top: 20px; width: 8px; height: 8px; background: #ef233c; border-radius: 50%;"></span>' : ''}
                                <div style="font-size: 13px; color: #333; padding-right: 15px; line-height: 1.4;">
                                    ${message}
                                </div>
                            </a>
                        `;
                    });
                } else {
                    html = `
                        <div class="p-4 text-center text-muted" style="font-size: 13px;">
                            Chưa có thông báo nào.
                        </div>
                    `;
                }

                document.querySelectorAll('.notification-list').forEach(list => {
                    list.innerHTML = html;
                    
                    // Re-bind click event for newly injected items
                    list.querySelectorAll('.notify-item.unread').forEach(item => {
                        item.addEventListener('click', function(e) {
                            const notifyId = this.dataset.id;
                            const markUrl = '{{ url("/notifications") }}/' + notifyId + '/mark-as-read';
                            fetch(markUrl, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                }
                            }).catch(function() {}); // Silently fail
                        });
                    });
                });

                // --- ACCOUNT PAGE SYNC ---
                const accountBadge = document.querySelector('.unread-badge-count');
                if (accountBadge) {
                    if (unreadCount > 0) {
                        accountBadge.style.display = 'inline-block';
                        accountBadge.innerText = unreadCount;
                    } else {
                        accountBadge.style.display = 'none';
                    }
                }

                const accountTabList = document.querySelector('.notification-list-tab');
                if (accountTabList) {
                    let tabHtml = '';
                    notifs.forEach(n => {
                        const link = (n.data && n.data.url) ? n.data.url : ((n.data && n.data.link) ? n.data.link : 'javascript:void(0)');
                        const message = (n.data && n.data.message) ? n.data.message : 'Thông báo mới';
                        const isUnread = n.read_at === null;
                        const dateStr = n.created_at_human || 'Mới đây';
                        
                        tabHtml += `
                            <div class="notify-row d-flex align-items-center p-3 border-bottom ${isUnread ? 'bg-light' : ''}" style="position: relative; transition: background 0.2s;">
                                <div class="flex-grow-1">
                                    <a href="${link}" class="text-decoration-none text-dark mark-read-manual" data-id="${n.id}">
                                        <div class="fw-semibold mb-1" style="font-size: 0.95rem;">
                                            ${isUnread ? '<span class="badge bg-danger rounded-circle p-1 me-1" style="width:8px; height:8px; display:inline-block;"></span>' : ''}
                                            ${message}
                                        </div>
                                        <div class="text-muted small"><i class="bi bi-clock me-1"></i>${dateStr}</div>
                                    </a>
                                </div>
                                <div>
                                    ${isUnread ? `<button class="btn btn-sm btn-link text-decoration-none p-0 mark-read-manual" data-id="${n.id}" title="Đánh dấu đã đọc"><i class="bi bi-check2-circle fs-5"></i></button>` : ''}
                                </div>
                            </div>
                        `;
                    });
                    if(tabHtml !== '') {
                        accountTabList.innerHTML = tabHtml;
                    }
                }
            }).catch(function() {}); // Silently handle network errors
    }, 30000); 
    @endauth
});
</script>
@endpush
