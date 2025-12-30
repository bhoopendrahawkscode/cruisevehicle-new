<template>
    <div>
      <FrontHeader/>
          <main>
           <slot></slot>
        </main>
      <FrontFooter/>
    </div>
  </template>
  <script>
   import '@theme_1/js/dashboard.js';
   import { defineAsyncComponent } from 'vue';
   export default {
        components: {
            FrontHeader: defineAsyncComponent(() =>
                import('../include/header.vue')
            ),
            FrontFooter: defineAsyncComponent(() =>
                import('../include/footer.vue')
            )
        },
        async created() {
            await this.loadCss(import.meta.env.VITE_APP_URL+'/front/theme_1/css/dashboard.css');
        },
        unmounted() {
            this.removeCss(import.meta.env.VITE_APP_URL+'/front/theme_1/css/dashboard.css');
        },
        methods: {
            loadCss(href) {
                return new Promise((resolve, reject) => {
                    const link = document.createElement('link');
                    link.href = href;
                    link.rel = 'stylesheet';
                    link.onload = resolve;
                    link.onerror = reject;
                    document.head.appendChild(link);
                });
            },
            removeCss(href) {
                const cssElements = document.querySelectorAll('link[href="' + href + '"]');
                cssElements.forEach((el) => el.remove());
            },
        },

    }
  </script>




