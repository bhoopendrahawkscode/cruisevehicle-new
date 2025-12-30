<template>
   <div class="wrapper d-flex align-items-stretch" style="margin-top: 175px;">
            <dashboardSidebar/>
            <!-- Page Content  -->
            <div id="content" class="p-4 p-md-5 pt-5">
                <div class="container bootstrap snippets bootdey">
                    <h1 class="text-primary">{{$t('Change Mobile')}}</h1>
                    <hr>
                    <div class="row">
                    <!-- left column -->
                    <div class="col-md-3">
                        <div class="text-center">
                        <!-- <img src="https://bootdey.com/img/Content/avatar/avatar7.png" class="avatar img-circle img-thumbnail" alt="avatar">
                        <h6>Upload a different photo...</h6>

                        <input type="file" class="form-control"> -->
                        </div>
                    </div>
                    <!-- edit form column -->
                    <div class="col-md-12 personal-info changeMobileFrm">
                        <Form ref="mobileFrm" class="form-horizontal"  @submit="handleChangeMobile">
                            <div class="form-group row">
                                <label class="col-lg-3 control-label">{{$t('Current Mobile Number')}}:</label>
                                <div class="col-lg-2">
                                    <Field  name="country_code" type="text" class="form-control" v-model="country_code" rules="required" placeholder="Country Code"/>
                                    <span class="txtRed"><ErrorMessage name="country_code" /></span>
                                </div>
                                <div class="col-lg-6">
                                    <Field  name="mobile_no" type="text" class="form-control" v-model="mobile_no" rules="mobile" placeholder="Mobile Number" />
                                    <!-- <a href="#" style="float: right;margin-left: 15px;" @click="resendOtp"  to="/">Resend OTP</a> -->
                                    <span class="txtRed"><ErrorMessage name="mobile_no" /></span>
                                </div>
                            </div>

                            <div class="form-group" style="float: right;margin-right: 8%;">
                                <button type="submit" class="btn btn-primary">{{$t('Update')}}</button>
                            </div>
                        </form>
                    </div>

                    <!-- otp varification -->
                    <div class="col-md-12 personal-info otpFrom" style="display: none;">
                        <Form ref="verifyFrm" class="form-horizontal"  @submit="verifyMobileOtp">
                            <!-- <div class="form-group row">
                                <label class="col-lg-3 control-label">{{$t('Current Mobile Number')}}:</label>
                                <div class="col-lg-2">
                                    <Field  name="verify_country_code" type="text" class="form-control" v-model="verify_country_code" rules="required" placeholder="Country Code"/>
                                    <span class="txtRed"><ErrorMessage name="verify_country_code" /></span>
                                </div>
                                <div class="col-lg-6">
                                    <Field  name="verify_mobile_no" type="text" class="form-control" v-model="verify_mobile_no" rules="required" placeholder="Mobile Number" />
                                    <span class="txtRed"><ErrorMessage name="verify_mobile_no" /></span>
                                </div>
                            </div> -->
                            <div class="form-group row">
                                <label class="col-lg-3 control-label">{{$t('OTP')}}:</label>
                                <div class="col-lg-8">
                                    <Field readonly="true" name="otp" type="text" class="form-control" v-model="otp" rules="required" placeholder="OTP" />
                                    <a href="#" style="float: right;margin-left: 15px;" @click="resendOtp"  to="/">Resend OTP</a>
                                    <span class="txtRed"><ErrorMessage name="otp" /></span>
                                </div>
                            </div>

                            <div class="form-group" style="float: right;margin-right: 8%;">
                                <button type="submit" class="btn btn-primary" @click="showChangeMobileForm" style="margin-right:10px">{{$t('Back')}}</button>
                                <button type="submit" class="btn btn-primary">{{$t('Verify')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
                </div>
                <hr>
            </div>
		</div>
</template>

<script>
import 'vue3-toastify/dist/index.css';
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import { defineAsyncComponent } from 'vue';
    export default {
        components: {dashboardSidebar: defineAsyncComponent(() =>
			import('../include/dashboard-sidebar.vue')
		)},
        data() {
            return {
                mobile_no : '',
                country_code : '',
                otp: '',
                verify_country_code: '',
                verify_mobile_no: ''
            }
        },
        mounted(){

        },
        methods: {
            handleChangeMobile: function() {
                this.$refs.mobileFrm.validate().then((success) => {
                        if (success) {
                            this.$axios.post('updateMobileNo', {
                            crossdomain: true,
                            mobile_no: this.mobile_no,
                            country_code: this.country_code,
                        })
                        .then(response => {

                            if(response.data.status == 200){
                                $('.changeMobileFrm').hide();
                                $('.otpFrom').show();
                                this.verify_country_code = this.country_code;
                                this.verify_mobile_no = this.mobile_no;
                                this.otp = response.data.data.otp;
                                toast.success(response.data.message);
                            }
                            else{
                                toast.error('Something went wrong!');
                            }

                        })
                        .catch(error => {
                            console.log(error);
                            //toast.error(error.response.data.message);
                        });
                    }
                    else{
                        console.log('Form is invalid.');
                    }
                });
            },
            showChangeMobileForm: function(){
                $('.otpFrom').hide();
                $('.changeMobileFrm').show();
            },
            verifyMobileOtp: function() {
                this.$refs.mobileFrm.validate().then((success) => {
                        if (success) {
                            this.$axios.post('verifyMobileOtp', {
                            otp : this.otp,
                            mobile_no: this.verify_mobile_no,
                            country_code: this.verify_country_code,
                        })
                        .then(response => {
                            if(response.data.status == 200){
                                $('.otpFrom').hide();
                                $('.changeMobileFrm').show();
                                toast.success(response.data.message);
                            }
                            else{
                                toast.error('Something went wrong!');
                            }

                        })
                        .catch(error => {
                            toast.error(error.response.data.message);
                        });
                    }
                    else{
                        console.log('Form is invalid.');
                    }
                });
            },
            resendOtp: function(){
                this.$axios.get('resendMobileOtp')
                .then(response => {
                    this.otp = response.data.data.otp;
                    toast.success(response.data.message);
                })
                .catch(error => {
                    console.log('error');
                });

            },
        },
    }
</script>
