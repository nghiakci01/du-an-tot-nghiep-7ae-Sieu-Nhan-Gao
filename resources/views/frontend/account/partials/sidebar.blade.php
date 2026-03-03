<div class="dashboard_tab_button">
    <ul role="tablist" class="nav flex-column dashboard-list">
        <li><a href="{{ route('account.index') }}#account-details" {!! request()->routeIs('account.index') ? 'data-bs-toggle="tab"' : '' !!} class="nav-link {{ request()->routeIs('account.index') && !request()->routeIs('account.orders.show') ? 'active' : '' }}">{{ __('messages.account_details') }}</a></li>
        <li><a href="{{ route('account.index') }}#dashboard" {!! request()->routeIs('account.index') ? 'data-bs-toggle="tab"' : '' !!} class="nav-link">{{ __('messages.dashboard') }}</a></li>
        <li> <a href="{{ route('account.index') }}#orders" {!! request()->routeIs('account.index') ? 'data-bs-toggle="tab"' : '' !!} class="nav-link {{ request()->routeIs('account.orders.show') ? 'active' : '' }}">{{ __('messages.orders') }}</a></li>
        <li> <a href="{{ route('account.index') }}#coupons" {!! request()->routeIs('account.index') ? 'data-bs-toggle="tab"' : '' !!} class="nav-link">{{ __('messages.my_coupons') }}</a></li>
        <li><a href="{{ route('account.index') }}#wishlist" {!! request()->routeIs('account.index') ? 'data-bs-toggle="tab"' : '' !!} class="nav-link">{{ __('messages.my_wishlist') }}</a></li>
        <li>
            <a href="{{ route('logout') }}" class="nav-link"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                {{ __('messages.logout') }}
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </li>
    </ul>
</div>
