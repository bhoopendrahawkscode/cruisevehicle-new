<template>
    <div class="list-inline-item">
        <select class="" id="languageSelect" name="selectedLanguage" v-model="selectedLanguage" @change="changeLanguage">
            <option value="en">{{ $t('English') }}</option>
            <option value="fr">{{ $t('French') }}</option>
        </select>
        <!-- <button @click="changeLanguage('en')">{{ $t('English') }}</button>
        <button @click="changeLanguage('fr')">{{ $t('French') }}</button> -->
    </div>
</template>

<script>
import { useAppStore } from '@/store'
export default {
    data() {
        return {
            selectedLanguage:  useAppStore.locale || 'en', // set a default language
        };
   },
   mounted() {
        this.setLanguage();
    },
    computed: {
        locale() {
            return useAppStore().locale;
        },
    },
    methods: {
        changeLanguage() {
            const lang = this.selectedLanguage;
            this.$i18n.locale = lang;
            useAppStore().setLocale(lang);
            const currentRoute = this.$route;
            if(currentRoute.name == 'homeMain'){
                window.location.href =   import.meta.env.VITE_APP_URL+"/"+lang;
            }else{
                const newRoute = { name: currentRoute.name, params: { lang } };
                this.$router.push(newRoute);
            }
        },
        setLanguage(){
            this.selectedLanguage       =this.$i18n.locale
        }
    },
};
</script>
