@php
    $isIndex = request()->routeIs('account.index');
    $isOrders = request()->routeIs('account.orders.show');
@endphp
<div class="dashboard_tab_button">
    <ul role="tablist" class="nav flex-column dashboard-list">
        <li><a href="{{ route('account.index') }}#dashboard" {!! $isIndex ? 'data-bs-toggle="tab" data-bs-target="#dashboard"' : '' !!} class="nav-link">{{ __('messages.dashboard') }}</a></li>
        
        @if(Auth::check())
            <li><a href="{{ route('account.index') }}#wishlist" {!! $isIndex ? 'data-bs-toggle="tab" data-bs-target="#wishlist"' : '' !!} class="nav-link">{{ __('messages.my_wishlist') }}</a></li>
        @endif
        
        <li><a href="{{ route('account.index') }}#account-details" {!! $isIndex ? 'data-bs-toggle="tab" data-bs-target="#account-details"' : '' !!} class="nav-link {{ $isIndex ? 'active' : '' }}" id="nav-account-details">{{ __('messages.account_details') }}</a></li>
        
        <li><a href="{{ route('account.index') }}#orders" {!! $isIndex ? 'data-bs-toggle="tab" data-bs-target="#orders"' : '' !!} class="nav-link {{ $isOrders ? 'active' : '' }}" id="nav-orders">{{ __('messages.orders') }}</a></li>
        
        @if(Auth::check())
            <li><a href="{{ route('account.index') }}#coupons" {!! $isIndex ? 'data-bs-toggle="tab" data-bs-target="#coupons"' : '' !!} class="nav-link">{{ __('messages.my_coupons') }}</a></li>
        @endif
        
        @if(Auth::check())
        <li>
            <a href="{{ route('logout') }}" class="nav-link" style="border-bottom: 0 !important;"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                {{ __('messages.logout') }}
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </li>
        @endif
    </ul>
</div>

<script>
    // Dùng Vanilla JS để điều chỉnh lại active khi user truy cập thẳng vào #hash trên URL
    document.addEventListener("DOMContentLoaded", function() {
        if (window.location.hash) {
            let activeLinks = document.querySelectorAll('.dashboard_tab_button .nav-link');
            activeLinks.forEach(link => link.classList.remove('active'));
            
            let targetLink = document.querySelector('.dashboard_tab_button a[href*="' + window.location.hash + '"]');
            if(targetLink) {
                targetLink.classList.add('active');
            }
        }
    });
</script>
