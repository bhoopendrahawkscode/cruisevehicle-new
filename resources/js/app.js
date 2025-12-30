import "bootstrap/dist/css/bootstrap.min.css"
import '@fortawesome/fontawesome-free/css/all.css';
import {createApp} from 'vue';
import { createPinia } from 'pinia';
// Import Slick CSS
import 'slick-carousel/slick/slick.css';
import 'slick-carousel/slick/slick-theme.css';
// Import Slick JS
import 'slick-carousel/slick/slick.min.js';
import App from './App.vue';
import 'bootstrap';


import * as VeeValidatePlugin from './validation.js';
import { ErrorMessage, Field, Form } from 'vee-validate';
import { createI18n } from 'vue-i18n';
import enMessages from './lang/en.json';
import frMessages from './lang/fr.json';
import vue3GoogleLogin from 'vue3-google-login';
import axios from 'axios';
import router from './router.js';
import LoadingSpinner from './component/include/LoadingSpinner.vue';
import piniaPluginPersistedstate from 'pinia-plugin-persistedstate'
import VueLazyload from 'vue-lazyload'

import { useAppStore } from '@/store'


const pinia = createPinia();
pinia.use(piniaPluginPersistedstate)
const app = createApp(App);
app.use(pinia);


import loadimage from './assets/loading.gif';
import errorimage from './assets/noImage.png';

app.use(VueLazyload, {
  preLoad: 1.3,
  error: errorimage,
  loading: loadimage,
  attempt: 1
})


// Register VeeValidate components globally
app.component('loading-spinner', LoadingSpinner);
app.component('ErrorMessage', ErrorMessage);
app.component('Field', Field);
app.component('Form', Form);
app.use(VeeValidatePlugin);
app.use(vue3GoogleLogin, {
    clientId: '758121190135-nka1qmi379i7evrkpcp3ds9bhl7a142i.apps.googleusercontent.com'
  })
app.use(router);

const currentUrl        =   window.location.href;
const url               =   new URL(currentUrl);
let defaultLanguage     =   'en';
if(url.pathname != '/' && url.pathname != ''){
    defaultLanguage    =  url.pathname.split("/");
    defaultLanguage    =  defaultLanguage[1]
}
const i18n = createI18n({
    locale: defaultLanguage,
    fallbackLocale: 'en',
    messages: {
        en: enMessages,
        fr: frMessages,
    },
});

app.use(i18n);



axios.defaults.baseURL = import.meta.env.VITE_APP_URL +'/api/V1/'; //pGupta import laravel env variables
app.config.globalProperties.$axios = axios; //pGupta
axios.interceptors.request.use(
    config => {
        config.crossdomain = true;
        if (useAppStore().token) {
            config.headers['Authorization'] = `Bearer ${useAppStore().token}`;
        }
        if(useAppStore().locale){
            config.headers['Locale'] =  useAppStore().locale;
        }
        return config;
    },
    error => {
      return Promise.reject(error);
    }
  );


app.mount('#app');



