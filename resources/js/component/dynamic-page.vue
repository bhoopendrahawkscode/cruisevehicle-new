<template>
    <section class="page-title-section overlay" :style="{ backgroundImage:'url(front/theme_1/images/backgrounds/page-title.jpg)'}">
        <div class="container">

        </div>
    </section>
    <section class="section" :key="$route.fullPath">
        <div class="container">
            <div class="row">
                <loading-spinner v-if="isLoading"></loading-spinner>
                <div v-else>
                    <div class="col-12">
                        <h2 class="section-title">{{ this.title }}</h2>
                        <div v-html="content"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
<script>
    export default {
        data(){
            return {
                isLoading: true,
                title:'',
                content: '',
            }
        },
        mounted(){
          this.getData();
        },
        methods: {
            getData() {
                this.$axios.get('get-pages', {
                    params: {
                        slug: this.$route.params.slug
                    }
                })
                .then(response => {
                    if(response.data.status == 200){
                        this.isLoading = false;
                        this.title = response.data.data.cms_translation.title;
                        this.content = response.data.data.cms_translation.body;

                    }
                })
                .catch(error => {
                    console.error(error);
                });
            },
        },
        watch: { //pGupta
            '$route'(to, from) {
                this.getData();
                // Perform actions when the route changes
                // You can access the new route parameters using 'to.params'
                // And the old route parameters using 'from.params'
                console.log('Route changed!');
                console.log('New params:', to.params);
                console.log('Old params:', from.params);
                // You can trigger any necessary actions or re-renders here
            }
        }
    }
</script>


