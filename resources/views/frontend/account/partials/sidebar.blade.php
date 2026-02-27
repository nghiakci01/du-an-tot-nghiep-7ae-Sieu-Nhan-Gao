<div class="dashboard_tab_button">
    <ul role="tablist" class="nav flex-column dashboard-list">
        <li><a href="{{ route('account.index') }}#dashboard" data-bs-toggle="tab" class="nav-link active">{{ __('messages.dashboard') }}</a></li>
        <li> <a href="{{ route('account.index') }}#orders" data-bs-toggle="tab" class="nav-link">{{ __('messages.orders') }}</a></li>
        <li> <a href="{{ route('account.index') }}#coupons" data-bs-toggle="tab" class="nav-link">{{ __('messages.my_coupons') }}</a></li>
        <li><a href="{{ route('account.index') }}#account-details" data-bs-toggle="tab" class="nav-link">{{ __('messages.account_details') }}</a></li>
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
