// router.js
import { createRouter, createWebHistory} from 'vue-router';

const Home = () => import('./component/index.vue')
const DynamicPage = () => import('./component/dynamic-page.vue')
const Faq = () => import('./component/faq.vue')
const Blog = () => import('./component/blog.vue')
const BlogDetails = () => import('./component/blog-details.vue')
const Standout = () => import('./component/standout.vue')
const UserDashboard = () => import('./component/dashboard/index.vue')
const ChangePassword = () => import('./component/dashboard/change-password.vue')
const ChangeMobile = () => import('./component/dashboard/change-mobile.vue')
const Page404 = () => import('./component/404.vue')

import { useAppStore } from '@/store'
import axios from 'axios';
const routes = [
   /* {
        path: '/',
            redirect: (to) => {
                var lang = to.params.lang || useAppStore().locale;
                return `/${lang}`;
            },

    },
    */
    { path: '/', name:'homeMain', component: Home },
    { path: '/:lang', name:'home', component: Home },
    { path: `/:lang/faq`, name:'faq', component: Faq },
    { path: `/:lang/blog`, name: 'blog', component: Blog},
    { path: `/:lang/standout`, name: 'standout', component: Standout},
    { path: `/:lang/dashboard`, name:'userDashboard', component: UserDashboard, meta: { requiresAuth: true } }, //pGupta
    { path: `/:lang/change-password`, name:'change-password', component: ChangePassword, meta: { requiresAuth: true } },
    { path: `/:lang/change-mobile`, name:'change-mobile', component: ChangeMobile, meta: { requiresAuth: true } },
    { path: `/pages/:slug`, name:'dynamic-page', component: DynamicPage },
    { path: `/:lang/pages/:slug`, name:'dynamic-page-lang', component: DynamicPage},


    { path: `/blog/category/:slug`, name:'blog-listing-category', component: Blog },
    { path: `/:lang/blog/category/:slug`, name:'blog-listing-category-lang', component: Blog},

    { path: `/blog/tag/:slug`, name:'blog-listing-tag', component: Blog },
    { path: `/:lang/blog/tag/:slug`, name:'blog-listing-tag-lang', component: Blog},

    { path: `/blog/:slug`, name:'blog-details', component: BlogDetails },
    { path: `/:lang/blog/:slug`, name:'blog-details-lang', component: BlogDetails},

    { path: `/:catchAll(.*)`, name:'404', component: Page404 },
  ];



const router = createRouter({
    history: createWebHistory(),
    routes,
 });


router.beforeEach((to, from, next) => {
    const languageArr = ['en', 'fr'];
    if (languageArr.includes(to.params.lang)) {
        const lang = to.params.lang || useAppStore().locale || 'en';
        useAppStore().setLocale(lang);
    }
    else if( to.name != "homeMain" && to.name != "404"){ // if route is not main page without language and not 404 then redirect to proper url with language
        next({ path: '/'+useAppStore().locale + to.path });
    }else{
        next();
    }


    if (to.matched.some(record => record.meta.requiresAuth)) {
      if (!useAppStore().token) {
        next({ path: '/', query: { redirect: to.fullPath } });
      } else {
            axios.get('check-auth', {
                headers: {
                    'Authorization': `Bearer ${useAppStore().token}`,
                },
            })
            .then(response => {
                if(response.status == 200){
                    next();
                }
                else{
                    useAppStore().setToken("");
                    next({ path: '/', query: { redirect: to.fullPath } });
                }
            })
            .catch(error => {
                next({ path: '/', query: { redirect: to.fullPath } });
            });

      }
    } else {
      next();
    }
  });


  export default router;
