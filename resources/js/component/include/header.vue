<template>
<header class="fixed-top header">
  <div class="top-header py-2 bg-white">
    <div class="container">
      <div class="row no-gutters">
        <div class="col-lg-4 text-center text-lg-left">
          <a class="text-color mr-3" href="callto:+443003030266"><strong>CALL</strong> +44 300 303 0266</a>
          <ul class="list-inline d-inline">
            <li class="list-inline-item mx-0"><a class="d-inline-block p-2 text-color" href="#"><i class="ti-facebook"></i></a></li>
            <li class="list-inline-item mx-0"><a class="d-inline-block p-2 text-color" href="#"><i class="ti-twitter-alt"></i></a></li>
            <li class="list-inline-item mx-0"><a class="d-inline-block p-2 text-color" href="#"><i class="ti-linkedin"></i></a></li>
            <li class="list-inline-item mx-0"><a class="d-inline-block p-2 text-color" href="#"><i class="ti-instagram"></i></a></li>
          </ul>
        </div>
        <div class="col-lg-8 text-center text-lg-right">
          <ul class="list-inline">
            <li class="list-inline-item"><a class="text-uppercase text-color p-sm-2 py-2 px-0 d-inline-block" id="mybutton" href="#" @click="openLoginModal" v-if="!isAuthenticated">{{ $t('LOGIN') }}</a></li>
            <li class="list-inline-item"><a class="text-uppercase text-color p-sm-2 py-2 px-0 d-inline-block" href="#" @click="openSignUpModal" v-if="!isAuthenticated">{{ $t('REGISTRATION') }}</a></li>
            <li class="list-inline-item" v-if="isAuthenticated">
                <router-link class="text-uppercase text-color p-sm-2 py-2 px-0 d-inline-block" :to="{ name: 'userDashboard', params: { lang: locale } }">{{$t('Dashboard')}}</router-link>
            </li>
            <li class="list-inline-item"><a class="text-uppercase text-color p-sm-2 py-2 px-0 d-inline-block" href="#" @click="handleLogout" v-if="isAuthenticated">{{ $t('LOGOUT') }}</a></li>
            <LanguageSwitcher />
        </ul>
        </div>
      </div>
    </div>
  </div>
  <!-- navbar -->
  <div class="navigation w-100" style="background-color: blueviolet;">
    <div class="container">
      <nav class="navbar navbar-expand-lg navbar-light p-0">
        <a class="navbar-brand" :href="homeURL"><img src="@theme_1/images/logo.png" alt="logo"></a>
        <button class="navbar-toggler rounded-0" type="button" data-toggle="collapse" data-target="#navigation"
          aria-controls="navigation" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navigation">
          <ul class="navbar-nav ml-auto text-center">
            <li :class="{ 'nav-item': true, 'active': isActiveRoute('home') }">
                <router-link class="nav-link" :to="{ name: 'home', params: { lang: locale } }">{{$t('home')}}</router-link>
            </li>
            <li :class="{ 'nav-item': true, 'active': isActiveRoute('dynamic-page-lang') && this.$route.params.slug == 'about-us' }">
              <router-link class="nav-link" :to="{ name: 'dynamic-page-lang', params: { lang: locale,slug:'about-us' } }">{{$t('About')}}</router-link>
            </li>
            <li :class="{ 'nav-item': true, 'active': isActiveRoute('dynamic-page-lang') && this.$route.params.slug == 'services'}">
              <router-link class="nav-link" :to="{ name: 'dynamic-page-lang', params: { lang: locale,slug:'services' } }">{{$t('Services')}}</router-link>
            </li>
            <li :class="{ 'nav-item': true, 'active': isActiveRoute('dynamic-page-lang') && this.$route.params.slug == 'terms-and-conditions'}">
              <router-link class="nav-link" :to="{ name: 'dynamic-page-lang', params: { lang: locale,slug:'terms-and-conditions' } }">{{$t('Terms')}}</router-link>
            </li>
            <li :class="{ 'nav-item': true, 'active': isActiveRoute('dynamic-page-lang') && this.$route.params.slug == 'privacy-and-policy'}">
              <router-link class="nav-link" :to="{ name: 'dynamic-page-lang', params: { lang: locale,slug:'privacy-and-policy' } }">{{$t('Privacy')}}</router-link>
            </li>
            <li :class="{ 'nav-item': true, 'active': isActiveRoute('faq') }">
              <router-link class="nav-link" :to="{ name: 'faq', params: { lang: locale } }">{{$t('Faq')}}</router-link>
            </li>
            <li :class="{ 'nav-item': true, 'active': isActiveRoute('standout') }">
              <router-link class="nav-link" :to="{ name: 'standout', params: { lang: locale } }">{{$t('Standout List')}}</router-link>
            </li>
            <li :class="{ 'nav-item': true, 'active': isActiveRoute('blog') }">
              <router-link class="nav-link" :to="{ name: 'blog', params: { lang: locale } }">{{$t('Blogs')}}</router-link>
            </li>
          </ul>
        </div>
      </nav>
    </div>
  </div>
</header>
 <!-- Modal -->
 <div class="modal fade headerModal" id="loginModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content rounded-0 border-0 p-4">
                    <div class="modal-header border-0">
                        <h3>Login</h3>
                        <button type="button" class="closeModal" @click="closeModal" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="login">
                            <div v-if="validationError">{{ validationError }}</div>
                            <Form ref="loginFrm" @submit="handleLogin">
                                <div class="col-12">
                                    <label>{{$t('Email')}}</label>
                                    <Field type="email" class="form-control mb-3" name="login_email" v-model="login_email" placeholder="Email" rules="email" />
                                    <span class="txtRed"><ErrorMessage name="login_email" /></span>
                                </div>
                                <div class="col-12">
                                    <label>{{$t('Password')}}</label>
                                    <Field type="password" class="form-control mb-3" name="login_password" v-model="login_password" placeholder="Password" rules="required" />
                                    <span class="txtRed"><ErrorMessage name="login_password" /></span>
                                </div>
                                <div class="row col-12">
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-success btn-sm" @click="loginWithProvider('facebook')"><img src="@theme_1/images/facebook.png"></button>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-success btn-sm" @click="loginWithProvider('google')"><img src="@theme_1/images/google.png"></button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <a href="#" style="float: right;" @click="forgotPassword">Forgot Password</a>
                                    </div>
                                </div>
                                <div class="col-12" style="margin-top:20px">
                                    <button type="submit" class="btn btn-primary">Login</button>
                                </div>
                            </Form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      <!--Register Modal -->
        <div class="modal fade headerModal" id="signupModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content rounded-0 border-0 p-4">
                    <div class="modal-header border-0">
                        <h3>Register</h3>
                        <button type="button" class="closeSignUpModal" @click="closeModal" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="login">
                            <div v-if="validationError">{{ validationError }}</div>
                            <Form ref="form" @submit="handleSubmit">
                                <div class="row">
                                    <div class="col-6">
                                        <Field name="first_name" type="text" class="form-control mb-3" v-model="first_name" rules="stringRequired" placeholder="First Name"/>
                                            <span class="txtRed"><ErrorMessage name="first_name" /></span>
                                    </div>
                                    <div class="col-6">
                                        <Field name="last_name" type="text" class="form-control mb-3" v-model="last_name" rules="stringRequired" placeholder="Last Name"/>
                                        <span class="txtRed"><ErrorMessage name="last_name" /></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-2">
                                        <Field name="country_code" type="text" class="form-control mb-3" v-model="country_code" rules="countryCodeRequired" placeholder="Code"/>
                                        <span class="txtRed"><ErrorMessage name="country_code" /></span>
                                    </div>
                                    <div class="col-4">
                                        <Field name="mobile_no" type="text" class="form-control mb-3" v-model="mobile_no" rules="mobile" placeholder="Mobile Number"/>
                                        <span class="txtRed"><ErrorMessage name="mobile_no" /></span>
                                    </div>
                                    <div class="col-6">
                                        <Field name="email" type="text" class="form-control mb-3" v-model="email" rules="email" placeholder="Email"/>
                                        <span class="txtRed"><ErrorMessage name="email" /></span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-6">
                                        <Field name="password" type="password" class="form-control mb-3" v-model="password" rules="required" placeholder="Password"/>
                                        <span class="txtRed"><ErrorMessage name="password" /></span>
                                    </div>
                                    <div class="col-6">
                                        <Field name="confirm_password" type="password" class="form-control mb-3" v-model="confirm_password" rules="required|confirmed:@password" placeholder="Confirm Password"/>
                                        <span class="txtRed"><ErrorMessage name="confirm_password" /></span>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">SIGN UP</button>
                                </div>
                            </Form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

     <!--Forgot Password Modal -->
        <div class="modal fade headerModal" id="forgotModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content rounded-0 border-0 p-4">
                    <div class="modal-header border-0">
                        <h3>Forgot Password</h3>
                        <button type="button" class="closeForgotModal" @click="closeModal" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="login">
                            <Form ref="forgotform" @submit="handleForgot">
                                <div class="row">
                                    <div class="col-2">
                                        <Field name="forgot_country_code" type="text" class="form-control mb-3" v-model="forgot_country_code" rules="countryCodeRequired" placeholder="Code"/>
                                        <span class="txtRed"><ErrorMessage name="forgot_country_code" /></span>
                                    </div>
                                    <div class="col-10">
                                        <Field name="forgot_mobile_no" type="text" class="form-control mb-3" v-model="forgot_mobile_no" rules="mobile" placeholder="Mobile Number"/>
                                        <span class="txtRed"><ErrorMessage name="forgot_mobile_no" /></span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <!-- <a href="#" style="float: right;margin-left: 15px;" @click="resendOtp"  to="/">Resend OTP</a> -->
                                        <a href="#" style="float: right;" @click="openLoginModal"  to="/">Login</a>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">Reset Password</button>
                                </div>
                            </Form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--Reset Password Modal -->
        <div class="modal fade headerModal" id="resetModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content rounded-0 border-0 p-4">
                    <div class="modal-header border-0">
                        <h3>Reset Password</h3>
                        <button type="button" class="closeSignUpModal" @click="closeModal" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="login">
                            <Form ref="resetform" @submit="handleReset">
                                <div class="row">
                                    <div class="col-6">
                                        <Field  name="reset_otp" type="text" class="form-control mb-3" v-model="reset_otp" rules="otpCodeRequired" placeholder="OTP"/>
                                        <span class="txtRed"><ErrorMessage name="reset_otp" /></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <Field name="reset_password" type="password" class="form-control mb-3" v-model="reset_password" rules="required" placeholder="Password"/>
                                        <span class="txtRed"><ErrorMessage name="reset_password" /></span>
                                    </div>
                                    <div class="col-6">
                                        <Field name="reset_confirm_password" type="password" class="form-control mb-3" v-model="reset_confirm_password" rules="required|confirmed:@reset_password" placeholder="Confirm Password"/>
                                        <span class="txtRed"><ErrorMessage name="reset_confirm_password" /></span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <a href="#" style="float: right;" @click="openLoginModal"  to="/">Login</a>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">Reset Password</button>
                                </div>
                            </Form>
                        </div>
                    </div>
                </div>
            </div>
        </div>


</template>

<script>
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import { useAppStore } from '@/store'
import { defineAsyncComponent } from 'vue';
export default {
    components: {
        LanguageSwitcher: defineAsyncComponent(() =>
            import('./LanguageSwitcher.vue')
        )
    },
   data() {
    return {
      first_name: '',
      last_name: '',
      email: '',
      country_code: '',
      mobile_no: '',
      password: '',
      confirm_password: '',
      login_email: '',
      login_password: '',
      forgot_country_code: '',
      forgot_mobile_no: '',
      reset_password: '',
      reset_confirm_password: '',
      reset_otp: '',
      isModalOpen: false,
      validationError: '',
      isAuthenticated: false,
      homeURL: import.meta.env.VITE_APP_URL,
    };
  },
  created(){
    this.isAuthenticated = useAppStore().token?true:false;
  },
  computed: {
    locale() {
      return useAppStore().locale;
    },
  },
  methods: {
    isActiveRoute(routeName) {
        return this.$route.name === routeName;
    },
    openLoginModal() {
      $('.headerModal').modal('hide');
      $('#loginModal').modal('show');
    },
    openSignUpModal() {
      $('.headerModal').modal('hide');
      $('#signupModal').modal('show');
    },
    closeModal() {
        $('.headerModal').modal('hide');
    },
    loginWithProvider(provider) {
            const popup = window.open(`en/google-login/${provider}`, 'socialLoginPopup', 'width=600,height=600');
            const handlePopup = () => {
                window.removeEventListener('message', handlePopup);
            };
                window.addEventListener('message', (event) => {
                if (event.origin === window.location.origin) {
                    if (event.data.status === 'success') {
                        console.error('Authentication success:', message);
                        localStorage.setItem('access_token', event.data.user.original.data.extras.tokenData.access_token);
                        window.Laravel.isAuthenticated = true;
                        this.isAuthenticated = true;
                        popup.close();
                        //toast.success(event.data.user.original.data.message);
                        $('#loginModal').modal('hide');
                        this.$router.push('/dashboard');
                    }
                    else{
                        console.error('Authentication error:', message);
                    }
                }
            });

            const pollPopup = setInterval(() => {
            if (popup.closed) {
              clearInterval(pollPopup);

              // Fetch the user status or perform other actions after authentication
              this.$axios.get(`check-auth-status`)
                .then(response => {
                  // Handle the response, redirect user, etc.

                  // If authentication was successful, post a message to the popup
                  if (response.data.status === 'success') {
                    popup.postMessage({ status: 'success' }, window.location.origin);
                  }
                })
                .catch(error => {
                  // Handle error
                });
            }
          }, 500);

        },
    handleLogin() {
      $('#server-error').text('');
      this.$refs.loginFrm.validate().then((success) => {
        if (success) {
            this.$axios.post('login', {
                crossdomain: true,
                email: this.login_email,
                password: this.login_password,
                loginType: "REGULAR"
            })
            .then(response => {
                toast.success(response.data.message);
               // this.login_email = '';
               // this.login_password = '';
                $('#loginModal').modal('hide');
                useAppStore().setToken(response.data.data.extras.tokenData.access_token);
                this.isAuthenticated = true;

                this.$router.push({name : 'userDashboard',params: {lang:this.locale}});


            })
            .catch(error => {
                console.log(error);
                if (error.response && error.response.status === 422) {
                    toast.error(error.response.data.message);
                }
            });
        }
        else {
          console.log('Form is invalid.');
        }
      });
    },
    handleSubmit() {
      $('#server-error').text('');
      this.$refs.form.validate().then((success) => {
        if (success) {
            this.$axios.post('register', {
                first_name: this.first_name,
                last_name: this.last_name,
                email: this.email,
                country_code: this.country_code,
                mobile_no: this.mobile_no,
                password: this.password,
            })
            .then(response => {
                toast.success(response.data.message);
                this.first_name = '';
                this.last_name = '';
                this.email = '',
                this.mobile_no = '',
                this.password = '';
                this.country_code = '',
                $('#signupModal').modal('hide');
            })
            .catch(error => {
                console.log(error);
                if (error.response && error.response.status === 422) {
                    toast.error(error.response.data.message);
                }
            });
        }
        else {
          console.log('Form is invalid.');
        }
      });
    },

    handleLogout(){
        if(!this.isAuthenticated){
            console.error('Access token is missing.');
            return;
        }
        else{
            this.$axios.post('logout', null, {
                headers:{
                    'Authorization': `Bearer ${useAppStore().token}`,
                }
            }).then((response) => {
                useAppStore().setToken("");
                window.Laravel.isAuthenticated = false;
                this.isAuthenticated = false;
                this.$router.push('/');

            })
            .catch(error => {
                toast.error(error);
            });
        }

    },

    forgotPassword(){
        this.forgot_country_code = '';
        this.forgot_mobile_no = '';
        $('.headerModal').modal('hide');
        $('#forgotModal').modal('show');
    },


    handleForgot(){
        this.$refs.forgotform.validate().then((success) => {
        if (success) {
            this.$axios.post('forgotPassword', {
                country_code: this.forgot_country_code,
                mobile_no: this.forgot_mobile_no,
            })
            .then(response => {
                if(response.data.status == 200){
                    toast.success(response.data.message);
                    $('#forgotModal').modal('hide');
                    this.reset_otp = response.data.data.otp;
                    $('#resetModal').modal('show');
                }
                else{
                    toast.error('Something went wrong!');
                }
            })
            .catch(error => {
                console.log(error);
                if (error.response && error.response.status === 422) {
                    toast.error(error.response.data.message);
                }
            });
        }
        else {
          console.log('Form is invalid.');
        }
      });
    },
    handleReset(){
        this.$refs.resetform.validate().then((success) => {
        if (success) {
            this.$axios.post('resetPassword', {
                country_code: this.forgot_country_code,
                mobile_no: this.forgot_mobile_no,
                otp: this.reset_otp,
                password: this.reset_password
            })
            .then(response => {
                this.reset_otp = '';
                this.reset_password = '';
                this.forgot_mobile_no = '';
                this.forgot_country_code = '';
                toast.success(response.data.message);
                $('#resetModal').modal('hide');
            })
            .catch(error => {
                console.log(error);
                if (error.response && error.response.status === 422) {
                    toast.error(error.response.data.message);
                }
            });
        }
        else {
          console.log('Form is invalid.');
        }
      });
    },

  }
};
</script>


