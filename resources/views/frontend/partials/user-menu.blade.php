<div class="top_right text-right user-menu-wrapper">
    <ul>
        <li class="top_links">
            @guest
                <a  href="{{ route('account.index') }}" class="user-account-link"><i class="ion-android-person"></i> {{ __('messages.my_account') }} <i class="ion-chevron-down"></i></a>
                <ul class="dropdown_links">
                    <li><a class="custom-dropdown-links" href="{{ route('account.index') }}#dashboard">{{ __('messages.dashboard') }}</a></li>
                    <li><a href="{{ route('login') }}">{{ __('messages.login') }}</a></li>
                </ul>
            @else
                <a href="#" class="user-account-link" style="display: flex; align-items: center; gap: 8px;">
                    @if(Auth::user()->avatar_url)
                        <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name }}" style="width: 25px; height: 25px; border-radius: 50%; object-fit: cover; border: 1px solid #ddd;">
                    @else
                        <i class="ion-android-person"></i>
                    @endif
                    <span style="max-width: 120px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ Auth::user()->name }}</span>
                    <i class="ion-chevron-down"></i>
                </a>
                <ul class="dropdown_links">
                    <li><a href="{{ route('account.index') }}#dashboard">{{ __('messages.dashboard') }}</a></li>
                    @if(Auth::user()->isAdmin())
                        <li><a href="{{ route('admin.dashboard') }}">{{ __('messages.admin') }}</a></li>
                    @endif  
                    <li><a href="{{ route('logout') }}" onclick="event.preventDefault(); this.parentElement.nextElementSibling.submit();">{{ __('messages.logout') }}</a></li>
                </ul>
                <form action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
            @endguest
        </li> 
    </ul>
</div>

<style>
.user-menu-wrapper ul li {
    position: relative;
    display: inline-block;
}
.user-menu-wrapper .user-account-link {
    cursor: pointer;
    font-size: 13px;
    font-weight: 500;
    color: inherit;
}
.user-menu-wrapper .dropdown_links {
    position: absolute;
    top: 100%;
    right: 0;
    min-width: 170px;
    background: #fff;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    border-radius: 4px;
    padding: 10px 0;
    display: none;
    z-index: 9999;
    text-align: left;
}
.user-menu-wrapper .top_links:hover .dropdown_links {
    display: block;
}
.user-menu-wrapper .dropdown_links li a {
    display: block;
    padding: 8px 20px;
    color: #444;
    transition: all 0.3s;
}
.user-menu-wrapper .dropdown_links li a:hover {
    color: #ff6a28;
    background: #f9f9f9;
}
/* Fix for header_six sticky visibility */
.sticky-header.sticky {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    z-index: 9999;
    background: #fff;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    display: block !important;
    animation: fadeInDown 0.5s ease;
}

.sticky-header.sticky .user-account-link,
.sticky-header.sticky .user-account-link i {
    color: #242424 !important;
}

.sticky-header.sticky .dropdown_links {
    background: #fff !important;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1) !important;
}
</style>
