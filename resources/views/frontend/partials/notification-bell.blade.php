{{-- Notification Bell Widget --}}
@php
    $unreadCount = auth()->check() ? auth()->user()->unreadNotifications->count() : 0;
    $notifications = auth()->check() ? auth()->user()->notifications()->take(5)->get() : [];
    
    // Helper function for icons (Blade level)
    if (!function_exists('getNotificationIcon')) {
        function getNotificationIcon($message) {
            $message = mb_strtolower($message);
            if (str_contains($message, 'thanh toán') || str_contains($message, 'thành công')) return 'ion-ios-checkmark-outline';
            if (str_contains($message, 'mới')) return 'ion-ios-cart-outline';
            if (str_contains($message, 'hoàn trở') || str_contains($message, 'hoàn tiền')) return 'ion-ios-undo-outline';
            if (str_contains($message, 'vận chuyển') || str_contains($message, 'giao hàng')) return 'ion-ios-paperplane-outline';
            return 'ion-ios-bell-outline';
        }
    }

    if (!function_exists('getNotificationColor')) {
        function getNotificationColor($message) {
            $message = mb_strtolower($message);
            if (str_contains($message, 'thanh toán') || str_contains($message, 'thành công')) return '#4ade80'; // Emerald 400
            if (str_contains($message, 'mới')) return '#60a5fa'; // Blue 400
            if (str_contains($message, 'hoàn trở') || str_contains($message, 'hoàn tiền')) return '#f87171'; // Red 400
            return '#94a3b8'; // Slate 400
        }
    }
@endphp

<div class="notification_link" style="position: relative; margin-right: 15px; display: inline-block;">
    <a href="javascript:void(0)" class="notification-toggle" aria-label="Xem thông báo" style="font-size: 24px; color: #1e293b; position: relative; display: inline-flex; align-items: center; transition: color 0.2s;">
        <i class="ion-android-notifications-none"></i>
        @auth
            @if($unreadCount > 0)
                <span class="notification-badge">{{ $unreadCount > 99 ? '99+' : $unreadCount }}</span>
            @endif
        @endauth
    </a>
    
    <!-- dropdown -->
    <div class="notification_dropdown">
        <div class="dropdown-header">
            <strong>Thông báo</strong>
            @auth
                @if($unreadCount > 0)
                    <a href="javascript:void(0)" class="mark-all-read">Đánh dấu đã đọc</a>
                @endif
            @endauth
        </div>
        
        <div class="notification-list" style="max-height: 400px; overflow-y: auto;">
            @auth
                @forelse($notifications as $notify)
                    @php
                        $msg = $notify->data['message'] ?? 'Bạn có thông báo mới';
                        $icon = getNotificationIcon($msg);
                        $color = getNotificationColor($msg);
                        $isUnread = is_null($notify->read_at);
                    @endphp
                    <a href="{{ isset($notify->data['url']) ? $notify->data['url'] : (isset($notify->data['link']) ? $notify->data['link'] : 'javascript:void(0)') }}" 
                       class="notify-item {{ $isUnread ? 'unread' : '' }}" 
                       data-id="{{ $notify->id }}">
                        
                        <div class="notify-icon" style="background-color: {{ $color }}15; color: {{ $color }};">
                            <i class="{{ $icon }}"></i>
                        </div>
                        
                        <div class="notify-content">
                            <div class="notify-message">
                                {{ str_replace('...', '…', $msg) }}
                            </div>
                            <div class="notify-time">
                                {{ $notify->created_at->diffForHumans() }}
                            </div>
                        </div>

                        @if($isUnread)
                            <span class="unread-dot"></span>
                        @endif
                    </a>
                @empty
                    <div class="empty-state">
                        <i class="ion-ios-information-outline"></i>
                        <p>Chưa có thông báo nào…</p>
                    </div>
                @endforelse
            @else
                <div class="empty-state">
                    <i class="ion-ios-locked-outline"></i>
                    <p>Vui lòng <a href="{{ route('login') }}" style="color: #ef233c; font-weight: 600;">đăng nhập</a> để xem thông báo.</p>
                </div>
            @endauth
        </div>
        
        @auth
        <div class="dropdown-footer">
            <a href="{{ route('account.index') }}#notifications">Xem tất cả thông báo</a>
        </div>
        @endauth
    </div>
</div>

<style>
/* Notification Bell Styles */
.notification_link { 
    display: inline-flex !important; 
    align-items: center; 
}

.notification-toggle:hover {
    color: #ef233c !important;
}

.notification-badge {
    position: absolute;
    top: -2px;
    right: -6px;
    background: #ef233c;
    color: white;
    border-radius: 20px;
    padding: 2px 6px;
    font-size: 10px;
    font-weight: 700;
    line-height: 1;
    border: 2px solid #fff;
    box-shadow: 0 2px 4px rgba(239, 35, 60, 0.2);
}

/* Dropdown Container with Glassmorphism */
.notification_dropdown {
    display: none;
    position: absolute;
    right: -10px;
    top: 100%;
    margin-top: 12px;
    width: 350px;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
    border-radius: 12px;
    z-index: 9999;
    border: 1px solid rgba(241, 245, 249, 0.8);
    overflow: hidden;
    animation: dropdownShow 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    transform-origin: top right;
}

@keyframes dropdownShow {
    from { opacity: 0; transform: translateY(10px) scale(0.95); }
    to { opacity: 1; transform: translateY(0) scale(1); }
}

/* Header */
.dropdown-header {
    padding: 16px 20px;
    background: #fff;
    border-bottom: 1px solid #f1f5f9;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.dropdown-header strong {
    font-size: 15px;
    color: #0f172a;
    font-weight: 700;
}

.mark-all-read {
    font-size: 12px;
    color: #ef233c;
    text-decoration: none;
    font-weight: 600;
    transition: opacity 0.2s;
}

.mark-all-read:hover {
    opacity: 0.8;
    text-decoration: underline;
}

/* Notification Item */
.notify-item {
    display: flex;
    padding: 14px 20px;
    border-bottom: 1px solid #f8fafc;
    text-decoration: none;
    transition: all 0.2s ease;
    align-items: flex-start;
    position: relative;
    background-color: #fff;
}

.notify-item:last-child {
    border-bottom: none;
}

.notify-item:hover {
    background-color: #f8fafc !important;
    transform: translateX(4px);
}

.notify-item.unread {
    background-color: #f1f5f940; /* Very subtle blue-gray tint */
}

.notify-icon {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    margin-right: 14px;
    flex-shrink: 0;
    transition: transform 0.2s;
}

.notify-item:hover .notify-icon {
    transform: scale(1.1);
}

.notify-content {
    flex-grow: 1;
    min-width: 0;
}

.notify-message {
    font-size: 13.5px;
    color: #334155;
    line-height: 1.5;
    margin-bottom: 4px;
    font-weight: 500;
}

.notify-item.unread .notify-message {
    color: #0f172a;
    font-weight: 600;
}

.notify-time {
    font-size: 11px;
    color: #94a3b8;
}

.unread-dot {
    width: 8px;
    height: 8px;
    background: #ef233c;
    border-radius: 50%;
    margin-left: 10px;
    margin-top: 6px;
    flex-shrink: 0;
    box-shadow: 0 0 0 2px #fff;
}

/* Empty State */
.empty-state {
    padding: 40px 20px;
    text-align: center;
    color: #94a3b8;
}

.empty-state i {
    font-size: 40px;
    margin-bottom: 12px;
    display: block;
    opacity: 0.5;
}

.empty-state p {
    font-size: 14px;
    margin: 0;
}

/* Footer */
.dropdown-footer {
    padding: 12px;
    text-align: center;
    border-top: 1px solid #f1f5f9;
    background: #fff;
}

.dropdown-footer a {
    font-size: 13px;
    color: #64748b;
    text-decoration: none;
    font-weight: 600;
    transition: color 0.2s;
}

.dropdown-footer a:hover {
    color: #0f172a;
}
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Helper for JS-side icon generation (Sync with Blade)
    function jGetNotificationIcon(message) {
        message = message.toLowerCase();
        if (message.includes('thanh toán') || message.includes('thành công')) return 'ion-ios-checkmark-outline';
        if (message.includes('mới')) return 'ion-ios-cart-outline';
        if (message.includes('hoàn trở') || message.includes('hoàn tiền')) return 'ion-ios-undo-outline';
        if (message.includes('vận chuyển') || message.includes('giao hàng')) return 'ion-ios-paperplane-outline';
        return 'ion-ios-bell-outline';
    }

    function jGetNotificationColor(message) {
        message = message.toLowerCase();
        if (message.includes('thanh toán') || message.includes('thành công')) return '#4ade80'; 
        if (message.includes('mới')) return '#60a5fa'; 
        if (message.includes('hoàn trở') || message.includes('hoàn tiền')) return '#f87171'; 
        return '#94a3b8'; 
    }

    // Handle all notification toggles
    document.querySelectorAll('.notification-toggle').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const wrapper = this.closest('.notification_link');
            const dropdown = wrapper.querySelector('.notification_dropdown');
            
            // Close other dropdowns
            document.querySelectorAll('.notification_dropdown').forEach(d => {
                if(d !== dropdown) d.style.display = 'none';
            });
            
            const isHidden = window.getComputedStyle(dropdown).display === 'none';
            dropdown.style.display = isHidden ? 'block' : 'none';
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
    const bindItemClick = (container) => {
        container.querySelectorAll('.notify-item.unread').forEach(function(item) {
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
    };

    bindItemClick(document);

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
                    document.querySelectorAll('.notify-item.unread').forEach(item => {
                        item.classList.remove('unread');
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

    // Auto Refresh Notifications every 30 seconds
    @auth
    var _notifPollTimer = setInterval(function() {
        if (!document.querySelector('.notification_link')) return; // Check if component still exists

        fetch('{{ route("notifications.list") }}', { headers: { 'Accept': 'application/json' } })
            .then(res => {
                if (res.status === 401) {
                    clearInterval(_notifPollTimer);
                    document.querySelectorAll('.notification-badge').forEach(b => b.style.display = 'none');
                    return null;
                }
                return res.json();
            })
            .then(data => {
                if (!data) return;

                const unreadCount = data.unread_count || 0;
                const notifs = data.notifications || [];

                // Update badges
                document.querySelectorAll('.notification-toggle').forEach(toggle => {
                    let badge = toggle.querySelector('.notification-badge');
                    if (unreadCount > 0) {
                        if (!badge) {
                            badge = document.createElement('span');
                            badge.className = 'notification-badge';
                            toggle.appendChild(badge);
                        }
                        badge.style.display = 'inline-block';
                        badge.innerText = unreadCount > 99 ? '99+' : unreadCount;
                    } else if (badge) {
                        badge.style.display = 'none';
                    }
                });

                // Update mark all as read visibility
                document.querySelectorAll('.mark-all-read').forEach(el => {
                    el.style.display = unreadCount > 0 ? 'inline' : 'none';
                });

                // Re-render list
                let html = '';
                if (notifs.length > 0) {
                    notifs.forEach(n => {
                        const link = (n.data && n.data.url) ? n.data.url : ((n.data && n.data.link) ? n.data.link : 'javascript:void(0)');
                        const message = ((n.data && n.data.message) ? n.data.message : 'Bạn có thông báo mới').replace(/\.\.\./g, '…');
                        const isUnread = n.read_at === null;
                        const icon = jGetNotificationIcon(message);
                        const color = jGetNotificationColor(message);
                        const time = n.created_at_human || 'Mới đây';
                        
                        html += `
                            <a href="${link}" class="notify-item ${isUnread ? 'unread' : ''}" data-id="${n.id}">
                                <div class="notify-icon" style="background-color: ${color}15; color: ${color};">
                                    <i class="${icon}"></i>
                                </div>
                                <div class="notify-content">
                                    <div class="notify-message">${message}</div>
                                    <div class="notify-time">${time}</div>
                                </div>
                                ${isUnread ? '<span class="unread-dot"></span>' : ''}
                            </a>
                        `;
                    });
                } else {
                    html = `
                        <div class="empty-state">
                            <i class="ion-ios-information-outline"></i>
                            <p>Chưa có thông báo nào…</p>
                        </div>
                    `;
                }

                document.querySelectorAll('.notification-list').forEach(list => {
                    list.innerHTML = html;
                    bindItemClick(list);
                });

                // Sync custom account pages if they exist
                const accountBadge = document.querySelector('.unread-badge-count');
                if (accountBadge) {
                    accountBadge.innerText = unreadCount;
                    accountBadge.style.display = unreadCount > 0 ? 'inline-block' : 'none';
                }
            }).catch(function() {}); 
    }, 30000); 
    @endauth
});
</script>
@endpush
