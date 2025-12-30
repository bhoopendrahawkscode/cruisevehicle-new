<template>
    <nav id="sidebar">
            <div class="custom-menu">
                <button type="button" id="sidebarCollapse" class="btn btn-primary">
                    <i class="fa fa-bars"></i>
                    <span class="sr-only">Toggle Menu</span>
                </button>
            </div>
            <div class="p-4">
                <h1><a href="index.html" class="logo">{{$t('Dashboard')}}</a></h1>
                <ul class="list-unstyled components mb-5">
                    <li :class="{ 'active': isActiveRoute('userDashboard') }">
                        <router-link  :to="{ name: 'userDashboard', params: { lang: locale } }"><span class="fa fa-home mr-3"></span>{{$t('Profile')}}</router-link>
                    </li>
                    <li :class="{ 'active': isActiveRoute('change-password') }">
                        <router-link  :to="{ name: 'change-password', params: { lang: locale } }"><span class="fa fa-home mr-3"></span>{{$t('Change Password')}}</router-link>
                    </li>
                    <li :class="{ 'active': isActiveRoute('change-mobile') }">
                        <router-link  :to="{ name: 'change-mobile', params: { lang: locale } }"><span class="fa fa-home mr-3"></span>{{$t('Change Mobile Number')}}</router-link>
                    </li>
                </ul>
            </div>
    </nav>
</template>
<script>
import { useAppStore } from '@/store'

export default {
    computed: {
        locale() {
        return useAppStore().locale;
        },
    },
    methods: {
        isActiveRoute(routeName) {
            return this.$route.name === routeName;
        },
    }
}
</script>
