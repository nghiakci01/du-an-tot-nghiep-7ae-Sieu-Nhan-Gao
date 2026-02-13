<div class="dashboard_tab_button">
    <ul role="tablist" class="nav flex-column dashboard-list">
        <li>
            <a href="#dashboard" data-bs-toggle="tab" class="nav-link active">
                <i class="bi bi-speedometer2 me-2"></i> {{ __('messages.dashboard') }}
            </a>
        </li>
        <li> 
            <a href="#orders" data-bs-toggle="tab" class="nav-link">
                <i class="bi bi-bag-check me-2"></i> {{ __('messages.orders') }}
            </a>
        </li>
        <li>
            <a href="#address" data-bs-toggle="tab" class="nav-link">
                <i class="bi bi-geo-alt me-2"></i> {{ __('messages.shipping_address') }}
            </a>
        </li>
        <li>
            <a href="#account-details" data-bs-toggle="tab" class="nav-link">
                <i class="bi bi-person-gear me-2"></i> {{ __('messages.account_details') }}
            </a>
        </li>
        <li>
            <a href="{{ route('logout') }}" class="nav-link text-danger"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="bi bi-box-arrow-right me-2"></i> {{ __('messages.logout') }}
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </li>
    </ul>
</div>
