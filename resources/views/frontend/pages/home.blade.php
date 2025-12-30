@extends('frontend.default_layout')
@section('content')
<div class="body_section">
            <section id="home" class="home_banner_block d-flex justify-content-center">
                <div class="container">
                    <div class="row banner_content_block align-items-center">
                        <div class="col-12 col-md-6 d-flex flex-column">
                            <div class="w-100 banner_data py-3 py-lg-5">
                                <h1 class="banner_title mb-3 mb-md-4">Simply dummy text of the printing and typesetting.</h1>
                                <p class="banner_info_text mb-3 mb-md-4">It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal</p>
                                <ul class="download_app">
                                    <li class="download_app_title mb-2"><span class="pb-2">Download app it's free</span></li>
                                    <li class="d-flex align-items-center pt-3">
                                        <a class="mr-3" href="#"><img src="{{ url('public/frontend/img/app_store_img.png')}}" alt="app_store_img" /></a>
                                        <a href="#"><img src="{{ url('public/frontend/img/google_play_img.png')}}" alt="google_play_img" /></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 text-center text-md-right">
                            <div class="banner_mobile"><img src="{{ url('public/frontend/img/banner_mobile.png')}}" alt="mobile"></div>
                        </div>
                    </div>
                </div>
            </section>

            <section id="app_features" class="app_features_sec py-4 py-md-5">
                <div class="container">
                    <div class="row mb-5">
                        <div class="col-12 text-center">
                            <h2 class="sec_title text-center mb-3">App Features</h2>
                            <div class="sec_para text-center w-100">
                                Funding freemium technology focus equity bootstrapping usernce niche market seed round <br/> development growth hacking retur on investment alpha.
                            </div>
                        </div>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-md-6 mb-md-0 mb-5">
                            <div class="app_features_image">
                                <img src="{{ url('public/frontend/img/app_features_mobileimg.png')}}" alt="app_features_mobileimg" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="app_features_content">
                                <div class="app_features_content_panel px-5 py-4">
                                    <div class="app_features_panel_title mb-2">Love Desire Partner</div>
                                    <div class="app_features_panel_para">Complain Management System is a platform which is double implied between customer and seller. It helps the seller to keep track of all the complaints lodged by the customer in respect of the product.</div>
                                </div>
                                <div class="app_features_content_panel px-5 py-4">
                                    <div class="app_features_panel_title mb-2">Love Desire Partner</div>
                                    <div class="app_features_panel_para">Complain Management System is a platform which is double implied between customer and seller. It helps the seller to keep track of all the complaints lodged by the customer in respect of the product.</div>
                                </div>
                                <div class="app_features_content_panel px-5 py-4">
                                    <div class="app_features_panel_title mb-2">Love Desire Partner</div>
                                    <div class="app_features_panel_para">Complain Management System is a platform which is double implied between customer and seller. It helps the seller to keep track of all the complaints lodged by the customer in respect of the product.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section id="about_us" class="about_sec py-4 py-md-5">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-md-6 mt-md-5 mt-0 mb-md-0 mb-5">
                            <h2 class="sec_title mb-4 mb-md-5 mt-0">About Us</h2>
                            <div class="pr-lg-5 pr-md-4">
                                <p class="sec_para mb-3">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form. </p>

                                <p class="sec_para mb-3">njected humour, or randomised words which don't look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text.</p>

                                <p class="sec_para mb-3">Many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form. </p>

                                <p class="sec_para mb-3">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form. There are many variations.</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="about_sec_side_img">
                                <img src="{{ url('public/frontend/img/about_us_side_img.png')}}" alt="about_us_side_img" />
                            </div>
                        </div>
                    </div>

                </div>
            </section>

            <!-- Main screen features -->
            <section id="showcash" class="main_screen_features py-4 py-md-5">
                <div class="container">
                    <div class="row">
                        <div class="col-12 text-center">
                            <h2 class="sec_title text-center mb-4 mb-md-5">Showcase</h2>
                        </div>
                    </div>
                </div>
                <div class="mb_iner_slid px-5">
                    <div class="owl-carousel owl-theme mob_sliderid">
                        <div class="item">
                            <div class="mobd_item"> <img src="{{ url('public/frontend/img/iphonex-img-inner01.png')}}" alt=""></div>
                        </div>
                        <div class="item">
                            <div class="mobd_item"> <img src="{{ url('public/frontend/img/iphonex-img-inner02.png')}}" alt=""></div>
                        </div>
                        <div class="item">
                            <div class="mobd_item"> <img src="{{ url('public/frontend/img/iphonex-img-inner03.png')}}" alt=""></div>
                        </div>
                        <div class="item">
                            <div class="mobd_item"> <img src="{{ url('public/frontend/img/iphonex-img-inner04.png')}}" alt=""></div>
                        </div>
                        <div class="item">
                            <div class="mobd_item"> <img src="{{ url('public/frontend/img/iphonex-img-inner05.png')}}" alt=""></div>
                        </div>
                        <div class="item">
                            <div class="mobd_item"> <img src="{{ url('public/frontend/img/iphonex-img-inner01.png')}}" alt=""></div>
                        </div>
                        <div class="item">
                            <div class="mobd_item"> <img src="{{ url('public/frontend/img/iphonex-img-inner02.png')}}" alt=""></div>
                        </div>
                        <div class="item">
                            <div class="mobd_item"> <img src="{{ url('public/frontend/img/iphonex-img-inner03.png')}}" alt=""></div>
                        </div>
                    </div>
                    <div class="iphone_img"> <img src="{{ url('public/frontend/img/iphonex-img.png')}}" alt=""> </div>
                </div>
            </section>

            <!-- Contact Us -->
            <section id="contact_us" class="contact_sec py-4 py-md-5">
                <div class="container">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-7 col-xl-7">
                            <h2 class="sec_title text-center mb-4 mb-md-5">Leave Us a Message</h2>
                            <form class="contact_form" id="contact_form" method="post" action="{{route('contactUs')}}">
                                @csrf
                                <div class="row">
                                    <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                                        <div class="form-group">
                                            <label for="" class="field_name">First Name</label>
                                            <input class="form-control" name="first_name" type="text" placeholder="First Name" required>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                                        <div class="form-group">
                                            <label for="" class="field_name">Last Name</label>
                                            <input class="form-control" name="last_name" type="text" placeholder="Last Name" required>
                                        </div>
                                    </div>

                                    <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                                        <div class="form-group">
                                            <label for="" class="field_name">Mobile</label>
                                            <input class="form-control" name="mobile" type="number" required>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                                        <div class="form-group">
                                            <label for="" class="field_name">Email Address</label>
                                            <input class="form-control" type="email" name="email" placeholder="Enter your email address" required>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="" class="field_name">Your Message</label>
                                            <textarea class="form-control" name="description" placeholder="" required></textarea>
                                        </div>
                                    </div>
                                </div>
                                <input class="btn" type="submit" value="Submit">
                            </form>
                        </div>
                        <div class="col-12 col-sm-12 col-md-12 col-lg-5 col-xl-5 pt-4 pt-lg-0">
                            <iframe  title="Contact" class="contact_map" src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d7119.064175712145!2d75.812621!3d26.854831!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x396db6747842586d%3A0x205bc4e86332fa4!2sOctal+IT+Solution!5e0!3m2!1sen!2sin!4v1540806557622" width="" height=""  allowfullscreen=""></iframe>
                        </div>
                    </div>
                </div>
            </section>

            <!-- FAQ Section-->
            <section id="faqs" class="faq_sec py-4 py-md-5">
                <div class="container">
                    <div class="text-center mb-5">
                        <h2 class="sec_title">FAQ's</h2>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-md-6 mb-md-0 mb-5">
                            <div class="faq_image">
                                <img src="{{ url('public/frontend/img/faq_side_img.png')}}" alt="faq_side_img" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="custom_accordian" id="custom-accordian">
                                <div class="card">
                                    <div class="card-header active-acc" id="headingOne">
                                        <h2 class="mb-0 d-flex align-items-center px-4 py-3 collapsed" data-toggle="collapse" data-target="#collapseOne">
                                            <span>There are many variations </span>
                                            <em class="fas fa-chevron-circle-down"></em>
                                        </h2>
                                    </div>
                                    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#custom-accordian">
                                        <div class="card-body p-4">
                                            <p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form. There are many variations.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header" id="headingTwo">
                                        <h2 class="mb-0 d-flex align-items-center px-4 py-3" data-toggle="collapse" data-target="#collapseTwo">
                                            <span>There are many variations of passages</span>
                                            <em class="fas fa-chevron-circle-down"></em>
                                        </h2>
                                    </div>
                                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#custom-accordian">
                                        <div class="card-body p-4">
                                            <p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form. There are many variations.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header" id="headingThree">
                                        <h2 class="mb-0 d-flex align-items-center px-4 py-3" data-toggle="collapse" data-target="#collapseThree">
                                            <span>Suffered alteration</span>
                                            <em class="fas fa-chevron-circle-down"></em>
                                        </h2>
                                    </div>
                                    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#custom-accordian">
                                        <div class="card-body p-4">
                                            <p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form. There are many variations.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header" id="headingFour">
                                        <h2 class="mb-0 d-flex align-items-center px-4 py-3" data-toggle="collapse" data-target="#collapseFour">
                                            <span>Suffered alteration in some form.</span>
                                            <em class="fas fa-chevron-circle-down"></em>
                                        </h2>
                                    </div>
                                    <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#custom-accordian">
                                        <div class="card-body p-4">
                                            <p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form. There are many variations.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header" id="headingFive">
                                        <h2 class="mb-0 d-flex align-items-center px-4 py-3" data-toggle="collapse" data-target="#collapseFive">
                                            <span>Alteration in some form.</span>
                                            <em class="fas fa-chevron-circle-down"></em>
                                        </h2>
                                    </div>
                                    <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#custom-accordian">
                                        <div class="card-body p-4">
                                            <p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form. There are many variations.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header" id="headingSix">
                                        <h2 class="mb-0 d-flex align-items-center px-4 py-3" data-toggle="collapse" data-target="#collapseSix">
                                            <span>There are many variations of</span>
                                            <em class="fas fa-chevron-circle-down"></em>
                                        </h2>
                                    </div>
                                    <div id="collapseSix" class="collapse" aria-labelledby="headingSix" data-parent="#custom-accordian">
                                        <div class="card-body p-4">
                                            <p>Fusce interdum. Maecenas eu elit sed nulla dignissim interdum. Sed laoreet. Aenean pede. Phasellus porta. Ut dictum nonummy diam. Sed a leo. Cras ullamcorper nibh. Sed laoreet.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Download App Section -->
            <section class="download_app_sec pb-5">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-12 col-md-5">
                            <div class="download_app_left_img">
                                <img src="{{ url('public/frontend/img/download_app_left_img.png')}}" alt="download_app_left_img" />
                            </div>
                        </div>
                        <div class="col-12 col-md-7 mt-5 pt-5">
                            <div class="download_app_content pl-md-5 pl-0 pt-md-5 pt-0">
                                <div class="download_app_content_title mb-4">Simply dummy text of the</div>
                                <div class="download_app_content_para mb-5 pr-5">If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden.</div>
                                <div class="download_app_free mb-3">Download app it's free</div>
                                <ul class="download_app d-flex align-items-center">
                                    <li class="mr-3"><a href="#"><img src="{{ url('public/frontend/img/app_store_img.png')}}" alt="download_app_img" /></a></li>
                                    <li><a href="#"><img src="{{ url('public/frontend/img/google_play_img.png')}}" alt="download_app_img" /></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        @push('scripts')
        <script type="text/javascript">

                var form = $('#contact_form');
                form.on('submit',function (e) {
                    e.preventDefault();
                    $.ajax({
                        type: form.attr('method'),
                        url: "{{ route('contactUs') }}",
                        dataType:"JSON",
                        data: form.serialize(),
                        success: function (data) {
                            if (data.status) {
                                toastr.success(data.message, '', {timeOut: 5000});
                                form.trigger('reset');
                            }else {
                                toastr.error(data.message, '', {timeOut: 5000});
                            }
                        },
                        error: function (data) {
                            toastr.error(trans("messages.somethingWentWrong"), '', {timeOut: 5000});
                        },
                    });
                });

        </script>
        @endpush

@endsection
