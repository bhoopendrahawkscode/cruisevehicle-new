<template>
     <header class="blog-header py-3">
        <div class="nav-scroller py-1 mb-2">
            <nav class="nav d-flex justify-content-between">
                <template v-for="item in blogCategories" :key="item?.id">
                     <router-link v-if="item?.slug" class="p-2 link-secondary"  :to="{ name: 'blog-listing-category-lang', params: { slug: item?.slug }}">{{item?.name}}</router-link>
                </template>
            </nav>
        </div>
    </header>
  </template>

  <script>
    export default {
        props: { //pGupta
            blogCategories: {
                type: Object,
                required: true,
            }
        },
    };
  </script>
