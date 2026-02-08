<footer class="footer_widgets">
    <div class="footer_top">
        <div class="container">
            <div class="row">
                <div class="col-lg-2 col-md-6 col-sm-6 col-6">
                    <div class="widgets_container">
                        <h3>Information</h3>
                        <div class="footer_menu">
                            <ul>
                                <li><a href="#">About Us</a></li>
                                <li><a href="#">Delivery Information</a></li>
                                <li><a href="#">Privacy Policy</a></li>
                                <li><a href="#">Terms &amp; Conditions</a></li>
                                <li><a href="#">Contact Us</a></li>
                                <li><a href="#">Returns</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 col-sm-6 col-6">
                    <div class="widgets_container">
                        <h3>Product categories</h3>
                        <div class="footer_menu">
                            <ul>
                                @foreach($categories->take(6) as $category)
                                    <li><a href="{{ route('shop', ['category' => $category->slug]) }}">{{ $category->name }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 col-sm-6 col-6">
                    <div class="widgets_container">
                        <h3>Manufacturer</h3>
                        <div class="footer_menu">
                            <ul>
                                <li><a href="#">Apple</a></li>
                                <li><a href="#">Samsung</a></li>
                                <li><a href="#">Sony</a></li>
                                <li><a href="#">Marshall</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 col-sm-6 col-6">
                    <div class="widgets_container">
                        <h3>Popular Tags</h3>
                        <div class="footer_menu">
                            <ul>
                                <li><a href="#">Headphone</a></li>
                                <li><a href="#">Bluetooth</a></li>
                                <li><a href="#">Portable</a></li>
                                <li><a href="#">Retina</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="widgets_container newsletter">
                        <h3>Join Our Newsletter Now</h3>
                        <div class="newleter-content">
                            <p>Exceptional quality. Ethical factories. Sign up to enjoy free U.S. shipping and returns on your first order.</p>
                             <div class="subscribe_form">
                                <form id="mc-form" class="mc-form footer-newsletter">
                                    <input id="mc-email" type="email" autocomplete="off" placeholder="Enter you email address here..." />
                                    <button id="mc-submit">Subscribe !</button>
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
