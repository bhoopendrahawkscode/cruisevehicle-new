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
  import { defineAsyncComponent } from 'vue';
  export default {
        components: {
            FrontHeader: defineAsyncComponent(() =>
                import('../include/header.vue')
            ),
            FrontFooter: defineAsyncComponent(() =>
                import('../include/footer.vue')
            )
        }
  }
  </script>
