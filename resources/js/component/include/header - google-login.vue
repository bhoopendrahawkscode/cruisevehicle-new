<template>
    <!-- header -->
<header class="fixed-top header">
  <!-- top header -->
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
            <li class="list-inline-item"><a class="text-uppercase text-color p-sm-2 py-2 px-0 d-inline-block" href="notice.html">{{ $t('NOTICE') }}</a></li>
            <li class="list-inline-item"><a class="text-uppercase text-color p-sm-2 py-2 px-0 d-inline-block" href="research.html">{{ $t('RESEARCH') }}</a></li>
            <li class="list-inline-item"><a class="text-uppercase text-color p-sm-2 py-2 px-0 d-inline-block" href="scholarship.html">{{ $t('SCHOLARSHIP') }}</a></li>
            <li class="list-inline-item"><a class="text-uppercase text-color p-sm-2 py-2 px-0 d-inline-block" href="#" @click="openLoginModal" v-if="!isAuthenticated">{{ $t('LOGIN') }}</a></li>
            <li class="list-inline-item"><a class="text-uppercase text-color p-sm-2 py-2 px-0 d-inline-block" href="#" @click="openSignUpModal" v-if="!isAuthenticated">{{ $t('REGISTRATION') }}</a></li>
            <li class="list-inline-item"><a class="text-uppercase text-color p-sm-2 py-2 px-0 d-inline-block" href="#" @click="handleLogout" v-if="isAuthenticated">{{ $t('LOGOUT') }}</a></li>

            <LanguageSwitcher />
        </ul>
        </div>
      </div>
    </div>
  </div>
  <!-- navbar -->
  <div class="navigation w-100">
    <div class="container">
      <nav class="navbar navbar-expand-lg navbar-light p-0">
        <a class="navbar-brand" href="{{import.meta.env.VITE_APP_URL}}"><img src="@theme_1/images/logo.png" alt="logo"></a>
        <button class="navbar-toggler rounded-0" type="button" data-toggle="collapse" data-target="#navigation"
          aria-controls="navigation" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navigation">
          <ul class="navbar-nav ml-auto text-center">
            <li class="nav-item active">
              <router-link class="nav-link" to="/">{{$t('Home')}}</router-link>
            </li>
            <li class="nav-item @@about">
                <router-link class="nav-link" to="/about">{{$t('About')}}</router-link>
            </li>
            <!-- <li class="nav-item @@blog">
              <a class="nav-link" href="blog.html">BLOG</a>
            </li>
            <li class="nav-item @@contact">
              <a class="nav-link" href="contact.html">CONTACT</a>
            </li> -->
          </ul>
        </div>
      </nav>
    </div>
  </div>
</header>
<!-- /header -->


 <!-- Modal -->
 <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content rounded-0 border-0 p-4">
                    <div class="modal-header border-0">
                        <h3>Login</h3>
                        <button type="button" class="closeLoginModal" @click="closeLoginModal" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="login">
                            <div v-if="validationError">{{ validationError }}</div>
                            <Form ref="loginFrm" @submit="handleLogin">
                                <div class="col-12">
                                    <Field type="email" class="form-control mb-3" name="login_email" v-model="login_email" placeholder="Email" rules="email" />
                                    <span class="txtRed"><ErrorMessage name="login_email" /></span>
                                </div>
                                <div class="col-12">
                                    <Field type="password" class="form-control mb-3" name="login_password" v-model="login_password" placeholder="Password" rules="required" />
                                    <span class="txtRed"><ErrorMessage name="login_password" /></span>
                                </div>
                                <div class="row col-12">
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-success btn-sm"><img src="@theme_1/images/facebook.png"></button>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-success btn-sm" @click="loginWithGoogle" :callback="goolecallback"><img src="@theme_1/images/google.png"></button>
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
      <!-- Modal -->
        <div class="modal fade" id="signupModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content rounded-0 border-0 p-4">
                    <div class="modal-header border-0">
                        <h3>Register</h3>
                        <button type="button" class="closeSignUpModal" @click="closeSignUpModal" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="login">
                            <div v-if="validationError">{{ validationError }}</div>
                            <Form ref="form" @submit="handleSubmit">
                                <div class="col-12">
                                    <Field name="name" type="text" class="form-control mb-3" v-model="name" rules="stringRequired" placeholder="Name"/>
                                    <span class="txtRed"><ErrorMessage name="name" /></span>
                                </div>
                                <div class="col-12">
                                    <Field name="email" type="email" class="form-control mb-3" v-model="email" rules="email" placeholder="Email"/>
                                    <span class="txtRed"><ErrorMessage name="email" /></span>
                                </div>
                                <div class="col-12">
                                    <Field name="password" type="password" class="form-control mb-3" v-model="password" rules="required" placeholder="Password"/>
                                    <span class="txtRed"><ErrorMessage name="password" /></span>
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
</template>

<script>
import axios from 'axios';
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import LanguageSwitcher from './LanguageSwitcher.vue';
import { googleTokenLogin } from "vue3-google-login";
import { decodeCredential } from 'vue3-google-login';

export default {
    components: {
        LanguageSwitcher,
    },
  data() {
    return {
      name: '',
      email: '',
      password: '',
      login_email: '',
      login_password: '',
      isModalOpen: false,
      validationError: '',
      isAuthenticated: false,
    };
  },
  mounted(){
    this.isAuthenticated = window.Laravel.isAuthenticated;
  },
  methods: {
    openLoginModal() {
      $('#loginModal').modal('show');
    },
    openSignUpModal() {
      $('#signupModal').modal('show');
    },
    closeLoginModal() {
      $('#loginModal').modal('hide');
    },
    closeSignUpModal() {
      $('#signupModal').modal('hide');
    },
    loginWithGoogle(response) {
        googleTokenLogin().then((tokenResponse) => {

            //this.goolecallback(tokenResponse);
        });
    },
    // goolecallback(response){
    //     try {
    //     const userData = decodeCredential(response.access_token);
    //     } catch (error) {
    //         console.error("Error decoding JWT:", error);
    //     }
    // },
    handleLogin() {
      $('#server-error').text('');
      this.$refs.loginFrm.validate().then((success) => {
        if (success) {
            axios.post('api/login', {
                crossdomain: true,
                email: this.login_email,
                password: this.login_password,
                loginType: "REGULAR"
            })
            .then(response => {
                toast.success(response.data.message);
                this.login_email = '';
                this.login_password = '';
                $('#loginModal').modal('hide');
                this.$router.push('/dashboard');
                localStorage.setItem('access_token', response.data.data.extras.tokenData.access_token);
                window.Laravel.isAuthenticated = true;
                this.isAuthenticated = true;

                // window.Laravel.isAuthenticated = true;
                // window.Laravel.userName = response.data.user.name;
                // localStorage.setItem('access_token', response.data.access_token);
                // this.$router.push('/dashboard');
            })
            .catch(error => {
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
            axios.post('api/register', {
                name: this.name,
                email: this.email,
                password: this.password,
            })
            .then(response => {
                toast.success(response.data.message);
                this.name = '';
                this.email = '';
                this.password = '';
                $('#signupModal').modal('hide');
            })
            .catch(error => {
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
            axios.post('api/logout', null, {
                headers:{
                    'Authorization': `Bearer ${localStorage.getItem('access_token')}`,
                }
            }).then((response) => {
                localStorage.removeItem('access_token');
                window.Laravel.isAuthenticated = false;
                this.isAuthenticated = false;
                this.$router.push('/');
            })
            .catch(error => {
                toast.error(error);
            });
        }

    },

  }
};
</script>
<style>
.txtRed{
    color:red;
}
</style>

