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
                                <li><a href="http://127.0.0.1:8000/news">{{ __('messages.delivery_information') }}</a></li>
                                <li><a href="http://127.0.0.1:8000/news">    {{ __('messages.privacy_policy') }}</a></li>
                                <li><a href="http://127.0.0.1:8000/news">{{ __('messages.terms_conditions') }}</a></li>
                                <li><a href="{{ route('contact.index') }}">{{ __('messages.contact_us') }}</a></li>
                                <li><a href="http://127.0.0.1:8000/news">{{ __('messages.returns') }}</a></li>
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
                            <p>{{ __('messages.address') }}: {{ $settings['site_address'] ?? 'Số 7 Ngõ 91 Lai Xá - Hoài Đức - Thành Phố Hà Nội - Việt Nam' }}</p>
                            <p>{{ __('messages.phone') }}: <a href="tel:{{ str_replace(' ', '', $settings['site_phone'] ?? '0354869999') }}">{{ $settings['site_phone'] ?? '0354869999' }}</a> </p>
                            <p>{{ __('messages.email') }}: {{ $settings['site_email'] ?? 'Elite@gmail.com' }}</p>
                            <ul>
                                <li><a href="https://twitter.com/" title="Twitter" target="_blank" rel="noopener noreferrer"><i class="fa fa-twitter"></i></a></li>
                                <li><a href="https://plus.google.com/" title="google-plus" target="_blank" rel="noopener noreferrer"><i class="fa fa-google-plus"></i></a></li>
                                <li><a href="{{ $settings['social_facebook'] ?? 'https://www.facebook.com/profile.php?id=61577211110743' }}" title="facebook" target="_blank" rel="noopener noreferrer"><i class="fa fa-facebook"></i></a></li>
                                <li><a href="{{ $settings['social_instagram'] ?? 'https://www.instagram.com/' }}" title="Instagram" target="_blank" rel="noopener noreferrer"><i class="fa fa-instagram"></i></a></li>
                                <li><a href="https://www.youtube.com/" title="youtube" target="_blank" rel="noopener noreferrer"><i class="fa fa-youtube"></i></a></li>
                            </ul>
                            <div class="bocongthuong_logo" style="margin-top: 15px;">
                                <a href="http://online.gov.vn/" target="_blank" rel="noopener noreferrer">
                                    <img src="https://tenten.vn/tin-tuc/wp-content/uploads/2024/09/Dinh-nghia-Da-thong-bao-voi-Bo-Cong-Thuong-la-gi.png" alt="Đã thông báo Bộ Công Thương" style="max-width: 360px;">
                                </a>
                            </div>
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
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mcForm = document.getElementById('mc-form');
            if (mcForm) {
                // Prevent default submission to avoid navigating away or showing default errors built-in somewhere
                $(mcForm).on('submit', function(e) {
                    e.preventDefault();
                    const mcEmail = document.getElementById('mc-email');
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    
                    if(mcEmail && mcEmail.value && emailRegex.test(mcEmail.value)) {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Thành công!',
                                text: 'Cảm ơn bạn đã đăng ký nhận bản tin!',
                                confirmButtonColor: '#ef233c',
                            });
                        } else {
                            alert('Cảm ơn bạn đã đăng ký nhận bản tin!');
                        }
                        mcForm.reset();
                    } else {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'error',
                                title: 'Lỗi!',
                                text: 'Vui lòng nhập địa chỉ email hợp lệ.',
                                confirmButtonColor: '#ef233c',
                            });
                        } else {
                            alert('Vui lòng nhập địa chỉ email hợp lệ.');
                        }
                    }
                });
            }
        });
    </script>
</footer>