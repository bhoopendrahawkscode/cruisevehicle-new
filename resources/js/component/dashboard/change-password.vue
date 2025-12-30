<template>
   <div class="wrapper d-flex align-items-stretch" style="margin-top: 175px;">
            <dashboardSidebar/>
            <!-- Page Content  -->
            <div id="content" class="p-4 p-md-5 pt-5">
                <div class="container bootstrap snippets bootdey">
                    <h1 class="text-primary">{{$t('Change Password')}}</h1>
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
                        <Form ref="passwordFrm" class="form-horizontal"  @submit="handleChangePassword">
                            <div class="form-group">
                                <label class="col-lg-3 control-label">{{$t('Current Password')}}:</label>
                                <div class="col-lg-8">
                                    <Field  name="current_password" type="password" class="form-control" v-model="current_password" rules="required"/>
                                    <span class="txtRed"><ErrorMessage name="current_password" /></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-3 control-label">{{$t('New Password')}}:</label>
                                <div class="col-lg-8">
                                    <Field  name="new_password" class="form-control" type="password"  v-model="new_password" rules="required"/>
                                    <span class="txtRed"><ErrorMessage name="new_password" /></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-3 control-label">{{$t('Confirm Password')}}:</label>
                                <div class="col-lg-8">
                                    <Field  name="confirm_password" class="form-control" type="password"  v-model="confirm_password" rules="required|confirmed:@new_password"/>
                                    <span class="txtRed"><ErrorMessage name="confirm_password" /></span>
                                </div>
                            </div>
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
import { defineAsyncComponent } from 'vue';
    export default {
        components: {dashboardSidebar: defineAsyncComponent(() =>
			import('../include/dashboard-sidebar.vue')
		)},
        data() {
            return {
                current_password : '',
                new_password: '',
            }
        },
        mounted(){

        },
        methods: {
            handleChangePassword: function() {
                this.$refs.passwordFrm.validate().then((success) => {
                        if (success) {
                            this.$axios.post('changePassword', {
                            current_password: this.current_password,
                            new_password: this.new_password,
                        })
                        .then(response => {
                            if(response.data.status == 200){
                                toast.success(response.data.message);
                                this.current_password = '';
                                this.new_password = '';
                                this.confirm_password = '';
                            }
                            else{
                                toast.error('Something went wrong');
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
        },
    }
</script>
