@extends('layouts.public')

@section('title', 'Contact Us | FashionStore')

@section('content')
    <!--breadcrumbs area start-->
    <div class="breadcrumbs_area other_bread">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumb_content">
                        <ul>
                            <li><a href="{{ route('welcome') }}">{{ __('messages.home') }}</a></li>
                            <li>/</li>
                            <li>{{ __('messages.contact') }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--breadcrumbs area end-->

    <!--contact area start-->
    <div class="contact_area">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <div class="contact_message content">
                        <h3>{{ __('messages.contact_us') }}</h3>
                        <p>{{ __('messages.contact_intro') }}</p>
                        <ul>
                            <li><i class="fa fa-fax"></i> {{ __('messages.address_label') }}: Số 7 Ngõ 91 Lai Xá - Hoài Đức - Thành Phố Hà Nội - Việt Nam</li>
                            <li><i class="fa fa-phone"></i> <a href="tel:0123456789">03575372804</a></li>
                            <li><i class="fa fa-envelope-o"></i> <a
                                    href="mailto:contact@fashionstore.vn">Elite@gmail.com</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="contact_message form">
                        <h3>{{ __('messages.send_message') }}</h3>


                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form id="contact-form" method="POST" action="{{ route('contact.send') }}">
                            @csrf
                            <p>
                                <label>{{ __('messages.name_required') }}</label>
                                <input name="name" placeholder="{{ __('messages.name_placeholder') }}" type="text" value="{{ old('name') }}"
                                    required>
                            </p>
                            <p>
                                <label>{{ __('messages.email_required') }}</label>
                                <input name="email" placeholder="{{ __('messages.email_placeholder') }}" type="text"
                                    value="{{ old('email') }}" required>
                            </p>
                            <p>
                                <label>{{ __('messages.subject_title') }}</label>
                                <input name="subject" placeholder="{{ __('messages.subject_placeholder') }}" type="text"
                                    value="{{ old('subject') }}" required>
                            </p>
                            <div class="contact_textarea">
                                <label>{{ __('messages.message_content') }}</label>
                                <textarea placeholder="{{ __('messages.message_placeholder') }}" name="message"
                                    class="form-control2" required>{{ old('message') }}</textarea>
                            </div>
                            <button type="submit">{{ __('messages.send') }}</button>
                            <p class="form-messege"></p>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--contact area end-->

    <!--contact map start-->
    <div class="contact_map">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="map-area">
                        <iframe id="googleMap" style="border: none;"
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.3251235419437!2d106.66408931533417!3d10.786834992313928!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752ed23c80767d%3A0x5a981a5efee9fd7d!2zVHLGsOG7nW5nIMSQ4bqhaSBo4buNYyBLaG9hIGjhu41jIFThu7Egbmhpw6puIC0gxJDhuqFpIGjhu41jIFF14buRYyBnaWEgVFAuSENN!5e0!3m2!1svi!2s!4v1642000000000!5m2!1svi!2s"
                            width="100%" height="450" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--contact map end-->
@endsection

@section('scripts')
    @if (session('success'))
        <script>
            Swal.fire({
                title: 'Thành công!',
                text: "{{ session('success') }}",
                icon: 'success',
                confirmButtonText: 'Đóng',
                confirmButtonColor: '#fe4536'
            });
        </script>
    @endif
@endsection