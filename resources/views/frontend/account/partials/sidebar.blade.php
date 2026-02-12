<div class="dashboard_tab_button">
    <ul role="tablist" class="nav flex-column dashboard-list">
        <li><a href="{{ route('account.index') }}#dashboard" data-bs-toggle="tab" class="nav-link active">Dashboard</a></li>
        <li> <a href="{{ route('account.index') }}#orders" data-bs-toggle="tab" class="nav-link">Orders</a></li>
        <li><a href="{{ route('account.index') }}#downloads" data-bs-toggle="tab" class="nav-link">Downloads</a></li>
        <li><a href="{{ route('account.index') }}#address" data-bs-toggle="tab" class="nav-link">Addresses</a></li>
        <li><a href="{{ route('account.index') }}#account-details" data-bs-toggle="tab" class="nav-link">Account details</a></li>
        <li>
            <a href="{{ route('logout') }}" class="nav-link"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                logout
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </li>
    </ul>
</div>
