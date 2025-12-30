<template>
    <section class="page-title-section overlay" :style="{ backgroundImage:'url(front/theme_1/images/backgrounds/page-title.jpg)'}">
        <div class="container">
        </div>
    </section>
    <loading-spinner v-if="isLoading"></loading-spinner>
    <template v-else>
        <main class="container mt-3" v-if="this?.data?.blogCategories.length">
            <BlogCategories :blogCategories="this?.data?.blogCategories"  />
        </main>
        <main class="container mt-3" v-if="this.data">
            <div class="row">
                <div class="col-md-8">
                    <article class="blog-post">
                        <h2 class="blog-post-title">{{data.post_translation.title}}</h2>
                        <p class="blog-post-meta">{{convertUTCInClientTz(data?.created_at)}} by <a href="#">{{(data?.author?.first_name)}} {{(data?.author?.last_name)}}</a></p>
                        <div v-html="data.post_translation.content"></div>
                    </article>
                </div>
                <div class="col-md-4">
                    <template v-for="item in this?.data?.allTags">
                         <router-link v-if="item?.slug" class="mr-1"  :to="{ name: 'blog-listing-tag-lang', params: { slug: item?.slug }}"><span class="badge badge-info">{{item?.name}}</span></router-link>
                    </template>
                </div>
            </div>
        </main>
    </template>
</template>
<script>

    import { defineAsyncComponent } from 'vue';
    import { CommonMethods } from '@/utils.js';
    import { useAppStore } from '@/store'
    import { RouterLink } from 'vue-router';

    export default {
        mixins: [CommonMethods], //pGupta
        components: {
            BlogCategories: defineAsyncComponent(() =>
              import('./include/blog-categories.vue') //pGupta
            ),
            RouterLink
        },
        computed: {
            locale() {
                 return useAppStore().locale;
            }
        },
        data(){
            return {
                isLoading: true,
                data: {

                },
            }
        },
        created(){
            this.getData();
        },
        methods: {

            async getData() {
                this.$axios.get('get-blog-details', {
                    params: {
                        slug: this.$route.params.slug,

                    },
                })
                .then(async (response) => {
                    if (response.data.status == 200) {
                        this.isLoading = false;
                        this.data = response.data.data;
                        console.log(response.data.data);
                    }
                })
                .catch((error) => {
                    console.error('Error fetching listData :', error);
                });
            }

        }
    }
</script>

