<footer class="footer_widgets footer_six">
    <div class="footer_top">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-2 col-md-6 col-sm-6 col-6">
                    <div class="widgets_container">
                        <h3>{{ __('messages.information') }}</h3>
                        <div class="footer_menu">
                            <ul>
                                <li><a href="{{ route('about') }}">{{ __('messages.about') }}</a></li>
                                <li><a href="#">{{ __('messages.delivery_information') }}</a></li>
                                <li><a href="#">{{ __('messages.privacy_policy') }}</a></li>
                                <li><a href="#">{{ __('messages.terms_conditions') }}</a></li>
                                <li><a href="{{ route('contact.index') }}">{{ __('messages.contact_us') }}</a></li>
                                <li><a href="#">{{ __('messages.returns') }}</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 col-sm-6 col-6">
                    <div class="widgets_container">
                        <h3>{{ __('messages.extras') }}</h3>
                        <div class="footer_menu">
                            <ul>
                                <li><a href="#">{{ __('messages.brands') }}</a></li>
                                <li><a href="#">{{ __('messages.gift_certificates') }}</a></li>
                                <li><a href="#">{{ __('messages.affiliate') }}</a></li>
                                <li><a href="#">{{ __('messages.specials') }}</a></li>
                                <li><a href="#">{{ __('messages.site_map') }}</a></li>
                                <li><a href="{{ route('account.index') }}">{{ __('messages.my_account') }}</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="widgets_container contact_us">
                        <h3>{{ __('messages.contact_us') }}</h3>
                        <div class="footer_contact">
                            <p>{{ __('messages.address') }}: Số 7 Ngõ 91 Lai Xá - Hoài Đức - Thành Phố Hà Nội - Việt Nam</p>
                            <p>{{ __('messages.phone') }}: <a href="tel:01234567890">01234567890</a> </p>
                            <p>{{ __('messages.email') }}: demo@example.com</p>
                            <ul>
                                <li><a href="#" title="Twitter"><i class="fa fa-twitter"></i></a></li>
                                <li><a href="#" title="google-plus"><i class="fa fa-google-plus"></i></a></li>
                                <li><a href="#" title="facebook"><i class="fa fa-facebook"></i></a></li>
                                <li><a href="#" title="youtube"><i class="fa fa-youtube"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="widgets_container newsletter">
                        <h3>{{ __('messages.join_newsletter') }}</h3>
                        <div class="newleter-content">
                            <p>{{ __('messages.newsletter_desc') }}</p>
                            <div class="subscribe_form">
                                <form id="mc-form" class="mc-form footer-newsletter">
                                    <input id="mc-email" type="email" autocomplete="off"
                                        placeholder="{{ __('messages.enter_email') }}" />
                                    <button id="mc-submit">{{ __('messages.subscribe') }}</button>
                                </form>
                                <div class="mailchimp-alerts text-centre">
                                    <div class="mailchimp-submitting"></div>
                                    <div class="mailchimp-success"></div>
                                    <div class="mailchimp-error"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>