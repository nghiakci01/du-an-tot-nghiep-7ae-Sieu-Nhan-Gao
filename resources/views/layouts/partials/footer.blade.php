<footer class="footer_widgets footer_six">
    <div class="footer_support" style="border-top: 1px solid #ebebeb; padding: 40px 0; background: #fff;">
        <div class="container-fluid">
            <div class="row justify-content-center" style="gap: 20px;">
                <div class="col-lg-auto col-md-12 mb-4 mb-lg-0 px-3 px-lg-4">
                    <div class="support_block" style="text-align: left;">
                        <p style="text-transform: uppercase; font-size: 14px; color: #333; margin-bottom: 15px; font-weight: 500;">GỌI MUA HÀNG ( 8:30 - 22:20 )</p>
                        <div class="support_phone" style="display: flex; align-items: center; gap: 15px; margin-bottom: 10px;">
                            <i class="fa fa-phone" style="background: #df2d2d; color: #fff; width: 40px; height: 40px; line-height: 40px; text-align: center; border-radius: 50%; font-size: 20px;"></i>
                            <span style="font-size: 24px; font-weight: 300; color: #333; letter-spacing: 1px;">096728.4444</span>
                        </div>
                        <p style="color: #666; font-size: 13px; margin-bottom: 0;">Tất cả các ngày trong tuần</p>
                    </div>
                </div>
                <div class="col-lg-auto col-md-12 mb-4 mb-lg-0 px-3 px-lg-4">
                    <div class="support_block" style="text-align: left;">
                        <p style="text-transform: uppercase; font-size: 14px; color: #333; margin-bottom: 15px; font-weight: 500;">GÓP Ý, KHIẾU NẠI ( 8:00 - 17:00 )</p>
                        <div class="support_phone" style="display: flex; align-items: center; gap: 15px; margin-bottom: 10px;">
                            <i class="fa fa-phone" style="background: #df2d2d; color: #fff; width: 40px; height: 40px; line-height: 40px; text-align: center; border-radius: 50%; font-size: 20px;"></i>
                            <span style="font-size: 24px; font-weight: 300; color: #333; letter-spacing: 1px;">096.895.90.50</span>
                        </div>
                        <p style="color: #666; font-size: 13px; margin-bottom: 0;">Các ngày trong tuần ( trừ ngày lễ )</p>
                    </div>
                </div>
                <div class="col-lg-auto col-md-12 px-3 px-lg-4">
                    <div class="support_block" style="text-align: left;">
                        <p style="text-transform: uppercase; font-size: 16px; color: #333; margin-bottom: 15px; font-weight: 500;">THEO DÕI CHÚNG TÔI</p>
                        <div class="social_follow" style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                            <a href="{{ $settings['social_facebook'] ?? 'https://www.facebook.com/profile.php?id=61577211110743' }}" target="_blank" rel="noopener noreferrer" style="color: #333; font-size: 32px; transition: color 0.3s;"><i class="fa fa-facebook-square"></i></a>
                            <a href="{{ $settings['social_instagram'] ?? 'https://www.instagram.com/' }}" target="_blank" rel="noopener noreferrer" style="color: #333; font-size: 32px; transition: color 0.3s;"><i class="fa fa-instagram"></i></a>
                            <a href="#" target="_blank" rel="noopener noreferrer" style="transition: opacity 0.3s;" onmouseover="this.style.opacity=0.7" onmouseout="this.style.opacity=1">
                                <img src="https://icon-icons.com/download-file?file=https%3A%2F%2Fimages.icon-icons.com%2F3915%2FPNG%2F512%2Fshopee_logo_icon_249631.png&id=249631&pack_or_individual=pack  " alt="Shopee" style="width: 32px; height: 32px; object-fit: contain; margin-bottom: 8px;">
                            </a>
                            <a href="#" target="_blank" rel="noopener noreferrer" style="transition: opacity 0.3s;" onmouseover="this.style.opacity=0.7" onmouseout="this.style.opacity=1">
                                <img src="https://www.citypng.com/public/uploads/preview/lazada-laz-square-white-icon-701751694968344ipp4hnbd4h.png?v=2026031223" alt="Lazada" style="width: 32px; height: 32px; object-fit: contain;">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
                                    <input id="mc-email" name="mc-email" type="email" autocomplete="off"
                                        placeholder="{{ __('messages.enter_email') }}" aria-label="Email Address for newsletter" />
                                    <button id="mc-submit" aria-label="Subscribe to newsletter">{{ __('messages.subscribe') }}</button>
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