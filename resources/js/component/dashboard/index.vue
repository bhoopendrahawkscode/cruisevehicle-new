<template>
   <div class="wrapper d-flex align-items-stretch" style="margin-top: 175px;">
            <dashboardSidebar/>
            <!-- Page Content  -->
            <div id="content" class="p-4 p-md-5 pt-5">
                <div class="container bootstrap snippets bootdey">
                    <h1 class="text-primary">{{$t('Edit Profile')}}</h1>
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
                    <div class="col-md-12 personal-info">
                        <Form ref="profileFrm" class="form-horizontal"  @submit="handleUpdateProfile">
                            <div class="form-group">
                                <label class="col-lg-3 control-label">{{$t('First Name')}}:</label>
                                <div class="col-lg-8">
                                    <Field  name="first_name" type="text" class="form-control" v-model="first_name" rules="required"/>
                                    <span class="txtRed"><ErrorMessage name="first_name" /></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-3 control-label">{{$t('Last Name')}}:</label>
                                <div class="col-lg-8">
                                    <Field  name="last_name" class="form-control" type="text"  v-model="last_name" rules="required"/>
                                    <span class="txtRed"><ErrorMessage name="last_name" /></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-3 control-label">{{$t('Email')}}:</label>
                                <div class="col-lg-8">
                                    <Field  name="email" class="form-control" type="text"  v-model="email" rules="email"/>
                                    <span class="txtRed"><ErrorMessage name="email" /></span>
                                </div>
                            </div>
                            <!-- <div class="form-group">
                                <label class="col-lg-3 control-label">{{$t('Mobile Number')}}:</label>
                                <div class="col-lg-8">
                                    <Field  name="mobile_no" class="form-control" type="text"  v-model="mobile_no" rules="mobile"/>
                                    <span class="txtRed"><ErrorMessage name="email" /></span>
                                </div>
                            </div> -->
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">{{$t('Update')}}</button>
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
import { useAppStore } from '@/store'
import { defineAsyncComponent } from 'vue';
    export default {
        components: {dashboardSidebar: defineAsyncComponent(() =>
			import('../include/dashboard-sidebar.vue')
		)},
        data() {
            return {
                first_name: '',
                last_name: '',
                email: '',
                mobile_no: '',
                user: [],
            }
        },
        computed: {
            locale() {
                return useAppStore().locale;
            },
            token() {
                return useAppStore().token;
            },
        },
        mounted(){
            this.getUserInfo();
        },
        methods: {
            getUserInfo(){
                        this.$axios.get('getProfile', {
                            headers:{
                                        'Authorization': `Bearer ${this.token}`,
                                    },
                            crossdomain: true,
                    })
                    .then(response => {
                        if(response.data.status == 200){
                           this.user = response.data.data;
                           this.first_name = this.user.first_name;
                           this.last_name = this.user.last_name;
                           this.email = this.user.email;
                        }
                    })
                .catch(error => {
                    console.error(error);
                });
            },
            handleUpdateProfile: function() {
                this.$refs.profileFrm.validate().then((success) => {
                        if (success) {
                            this.$axios.post('updateProfile', {
                            first_name: this.first_name,
                            last_name: this.last_name,
                            email: this.email
                        })
                        .then(response => {
                            toast.success(response.data.message);

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
        },
    }
</script>
