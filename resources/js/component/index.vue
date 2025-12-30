<template>

    <!-- hero slider -->
        <section class="hero-section overlay bg-cover" :style="{ backgroundImage:'url(front/theme_1/images/banner/banner-1.jpg)'}">
        <div class="container">
            <div class="slick-carousel">
            <!-- slider item -->
            <div class="hero-slider-item slide">
                <div class="row">
                <div class="col-md-8">
                    <h1 class="text-white" data-animation-out="fadeOutRight" data-delay-out="5" data-duration-in=".3" data-animation-in="fadeInLeft" data-delay-in=".1">{{ $t('Your bright future is our mission')}}</h1>
                    <p class="text-muted mb-4" data-animation-out="fadeOutRight" data-delay-out="5" data-duration-in=".3" data-animation-in="fadeInLeft" data-delay-in=".4">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                    tempor
                    incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exer</p>
                    <a href="#" class="btn btn-primary" data-animation-out="fadeOutRight" data-delay-out="5" data-duration-in=".3" data-animation-in="fadeInLeft" data-delay-in=".7">{{ $t('Apply Now')}} AAAA</a>
                </div>
                </div>
            </div>
            <!-- slider item -->
            <div class="hero-slider-item slide">
                <div class="row">
                <div class="col-md-8">
                    <h1 class="text-white" data-animation-out="fadeOutUp" data-delay-out="5" data-duration-in=".3" data-animation-in="fadeInDown" data-delay-in=".1">{{ $t('Your bright future is our mission')}}</h1>
                    <p class="text-muted mb-4" data-animation-out="fadeOutUp" data-delay-out="5" data-duration-in=".3" data-animation-in="fadeInDown" data-delay-in=".4">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                    tempor
                    incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exer</p>
                    <a href="#" class="btn btn-primary" data-animation-out="fadeOutUp" data-delay-out="5" data-duration-in=".3" data-animation-in="fadeInDown" data-delay-in=".7">{{ $t('Apply Now')}} BBBB</a>
                </div>
                </div>
            </div>
            <!-- slider item -->
            <div class="hero-slider-item slide">
                <div class="row">
                <div class="col-md-8">
                    <h1 class="text-white" data-animation-out="fadeOutDown" data-delay-out="5" data-duration-in=".3" data-animation-in="fadeInUp" data-delay-in=".1">{{ $t('Your bright future is our mission')}}</h1>
                    <p class="text-muted mb-4" data-animation-out="fadeOutDown" data-delay-out="5" data-duration-in=".3" data-animation-in="fadeInUp" data-delay-in=".4">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                    tempor
                    incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exer</p>
                    <a href="#" class="btn btn-primary" data-animation-out="fadeOutDown" data-delay-out="5" data-duration-in=".3" data-animation-in="zoomIn" data-delay-in=".7">{{ $t('Apply Now')}} CCCC</a>
                </div>
                </div>


            </div>
            </div>
        </div>
        </section>
    <!-- /hero slider -->

    <!-- banner-feature -->
        <section class="bg-gray">
        <div class="container-fluid p-0">
            <div class="row no-gutters">
            <div class="col-xl-4 col-lg-5 align-self-end">
                <img class="img-fluid w-100" src="@theme_1/images/banner/banner-feature.png" alt="banner-feature">
            </div>
            <div class="col-xl-8 col-lg-7">
                <div class="row feature-blocks bg-gray justify-content-between">
                <div class="col-sm-6 col-xl-5 mb-xl-5 mb-lg-3 mb-4 text-center text-sm-left">
                    <i class="ti-book mb-xl-4 mb-lg-3 mb-4 feature-icon"></i>
                    <h3 class="mb-xl-4 mb-lg-3 mb-4">{{ $t('Scholorship News')}}</h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore
                    et dolore magna aliqua. Ut enim ad</p>
                </div>
                <div class="col-sm-6 col-xl-5 mb-xl-5 mb-lg-3 mb-4 text-center text-sm-left">
                    <i class="ti-blackboard mb-xl-4 mb-lg-3 mb-4 feature-icon"></i>
                    <h3 class="mb-xl-4 mb-lg-3 mb-4">{{ $t('Our Notice Board')}}</h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore
                    et dolore magna aliqua. Ut enim ad</p>
                </div>
                <div class="col-sm-6 col-xl-5 mb-xl-5 mb-lg-3 mb-4 text-center text-sm-left">
                    <i class="ti-agenda mb-xl-4 mb-lg-3 mb-4 feature-icon"></i>
                    <h3 class="mb-xl-4 mb-lg-3 mb-4">{{ $t('Our Achievements')}}</h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore
                    et dolore magna aliqua. Ut enim ad</p>
                </div>
                <div class="col-sm-6 col-xl-5 mb-xl-5 mb-lg-3 mb-4 text-center text-sm-left">
                    <i class="ti-write mb-xl-4 mb-lg-3 mb-4 feature-icon"></i>
                    <h3 class="mb-xl-4 mb-lg-3 mb-4">{{ $t('Admission Now')}}</h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore
                    et dolore magna aliqua. Ut enim ad</p>
                </div>
                </div>
            </div>
            </div>
        </div>
        </section>
    <!-- /banner-feature -->

    <!-- about us -->
        <section class="section">
        <div class="container">
            <div class="row align-items-center">
            <div class="col-md-6 order-2 order-md-1">
                <h2 class="section-title">{{ $t('About Educenter')}}</h2>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat </p>
                <p>cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Sed ut perspiciatis unde omnis iste natus error sit voluptatem</p>
                <a href="#" class="btn btn-primary-outline">Learn more</a>
            </div>
            <div class="col-md-6 order-1 order-md-2 mb-4 mb-md-0">
            </div>
            </div>
        </div>
        </section>
    <!-- /about us -->

    <!-- blog -->
        <section class="section pt-0">
        <div class="container">
            <div class="row">
            <div class="col-12">
                <h2 class="section-title">{{ $t('Latest News')}}</h2>
            </div>
            </div>
            <div class="row justify-content-center">
        <!-- blog post -->
        <article class="col-lg-4 col-sm-6 mb-5 mb-lg-0">
            <div class="card rounded-0 border-bottom border-primary border-top-0 border-left-0 border-right-0 hover-shadow">
            <img class="card-img-top rounded-0" src="@theme_1/images/blog/post-1.jpg" alt="Post thumb">
            <div class="card-body">
                <!-- post meta -->
                <ul class="list-inline mb-3">
                <!-- post date -->
                <li class="list-inline-item mr-3 ml-0">August 28, 2019</li>
                <!-- author -->
                <li class="list-inline-item mr-3 ml-0">By Jonathon</li>
                </ul>
                <a href="#">
                <h4 class="card-title">{{ $t('The Expenses You Are Thinking.')}}'</h4>
                </a>
                <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipisicin</p>
                <a href="#" class="btn btn-primary btn-sm">{{ $t('Read More')}}</a>
            </div>
            </div>
        </article>
        <!-- blog post -->
        <article class="col-lg-4 col-sm-6 mb-5 mb-lg-0">
            <div class="card rounded-0 border-bottom border-primary border-top-0 border-left-0 border-right-0 hover-shadow">
            <img class="card-img-top rounded-0" src="@theme_1/images/blog/post-2.jpg" alt="Post thumb">
            <div class="card-body">
                <!-- post meta -->
                <ul class="list-inline mb-3">
                <!-- post date -->
                <li class="list-inline-item mr-3 ml-0">August 13, 2019</li>
                <!-- author -->
                <li class="list-inline-item mr-3 ml-0">By Jonathon Drew</li>
                </ul>
                <a href="#">
                <h4 class="card-title">{{ $t('Tips to Succeed in an Online Course')}}</h4>
                </a>
                <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipisicin</p>
                <a href="#" class="btn btn-primary btn-sm">{{ $t('Read More')}}</a>
            </div>
            </div>
        </article>
        <!-- blog post -->
        <article class="col-lg-4 col-sm-6 mb-5 mb-lg-0">
            <div class="card rounded-0 border-bottom border-primary border-top-0 border-left-0 border-right-0 hover-shadow">
            <img class="card-img-top rounded-0" src="@theme_1/images/blog/post-3.jpg" alt="Post thumb">
            <div class="card-body">
                <!-- post meta -->
                <ul class="list-inline mb-3">
                <!-- post date -->
                <li class="list-inline-item mr-3 ml-0">August 24, 2018</li>
                <!-- author -->
                <li class="list-inline-item mr-3 ml-0">By Alex Pitt</li>
                </ul>
                <a href="#">
                <h4 class="card-title">{{ $t('Orientation Program for the new students')}}</h4>
                </a>
                <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipisicin</p>
                <a href="#" class="btn btn-primary btn-sm">{{ $t('Read More')}}</a>
            </div>
            </div>
        </article>
        </div>
        </div>
        </section>
    <!-- /blog -->

    <!-- Testimonal -->
    <section class="section pt-0">
        <div class="container">
            <div class="row">
            <div class="col-12">
                <h2 class="section-title">{{ $t('Testimonial')}}</h2>
            </div>
            </div>
            <div class="row justify-content-center">


            <!-- Testimonal -->
            <div>
            <article class="col-lg-4 col-sm-6 mb-5 mb-lg-0" v-for="item in this.testimonalRecords" :key="item.index">
                <div class="card rounded-0 border-bottom border-primary border-top-0 border-left-0 border-right-0 hover-shadow">
                <img class="card-img-top rounded-0" src="@theme_1/images/blog/post-1.jpg" alt="Post thumb">
                <div class="card-body">
                    <ul class="list-inline mb-3">
                    <li class="list-inline-item mr-3 ml-0">August 28, 2019</li>
                    <li class="list-inline-item mr-3 ml-0">{{ item.testimonial_translation ? item.testimonial_translation.description : '' }}</li>
                    </ul>
                    <a href="#">
                    <h4 class="card-title">fdsfd {{ item.testimonial_translation ? item.testimonial_translation.description : '' }}</h4>
                    </a>
                    <p class="card-text"> fdf {{ item.testimonial_translation ? item.testimonial_translation.designation : '' }}</p>
                    <a href="#" class="btn btn-primary btn-sm">{{ $t('Read More')}}</a>
                </div>
                </div>
            </article>
            </div>

        </div>
        </div>
        </section>


        <!-- Follow these instructions, please! -->
<div class="container py-5">
  <h1 class="h2"> v5 Bug Report</h1>
  <p>Have a bug report to share with us? Use this template to reproduce an isolated test case so we can more easily identify the problem and hopefully find a fix.</p>
  <p>
  </p>
  <p>Once you're set, replace the contents of this page and share the link with us in a <a href="https://github.com/twbs/bootstrap/issues/new/choose" rel="noopener noreferrer" target="_blank">new issue</a>.</p>
  <p>Thanks! <span class="text-danger">♥</span></p>
</div>



    <!-- /Testimonal -->

  </template>
 <script>

 import $ from 'jquery';


 export default {
    data(){
        return {
            isLoading: true,
            testimonalRecords: {
                data: [],
                current_page: 1,
                last_page: 1,
            },
        }
    },
    mounted() {
        this.$nextTick(() => {
            this.initSlick();
        });
        this.getTestimonal();
    },
    methods:
        {
            initSlick() {
                $('.slick-carousel').not('.slick-initialized').slick({
                    autoplay: true,
                    autoplaySpeed: 7500,
                    pauseOnFocus: false,
                    pauseOnHover: false,
                    infinite: true,
                    arrows: true,
                    fade: true,
                    dots: true,
                });
            },
            getTestimonal() {
                    this.$axios.get('get-testimonials', {
                        crossdomain: true
                    })
                    .then(response => {
                        if(response.data.status == 200){
                            this.isLoading = false;
                            this.testimonalRecords = response.data.data;
                        }
                    })
                    .catch(error => {
                        console.error(error);
                    });
                },
        },
        beforeDestroy() {
            $('.slick-carousel').slick('unslick');
        }
 };
 </script>


