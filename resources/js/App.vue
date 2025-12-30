<template>
    <component :is="layout" >
      <router-view :key="locale"></router-view>
      <!-- we implemented locale as key to reload view when locale is changed -->
    </component>
 </template>
 <script>
    import '@theme_1/css/style.css';
    import '@theme_1/plugins/jQuery/jquery.min.js';
    import { defineAsyncComponent } from 'vue';

    import { useAppStore } from '@/store'

    export default {
        data() {
            return {
                layout: null,
            };
        },
        watch: {
            '$route': 'updateLayout', //pGupta
        },
        created() {
            this.updateLayout();
        },
        computed: {
            token() {
                return useAppStore().token;
            },
            locale() {
                return useAppStore().locale;
            },
        },
        methods: {
            updateLayout() {
                this.layout = this.token ? 'UserDashboardLayout' : 'FrontLayout';
            }
        },
        components: {
            FrontLayout: defineAsyncComponent(() =>
              import('./component/layout/front.vue')
          ),
            UserDashboardLayout: defineAsyncComponent(() =>
              import('./component/layout/user-dashboard.vue')
          ),
        },
    };
</script>


