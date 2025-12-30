<template>
        <!-- page title -->
            <section class="page-title-section overlay" :style="{ backgroundImage:'url(front/theme_1/images/backgrounds/page-title.jpg)'}">
            <div class="container">
                <div class="row">
                <div class="col-md-8">
                    <ul class="list-inline custom-breadcrumb">
                    <!-- <li class="list-inline-item"><a class="h2 text-primary font-secondary" href="@@page-link">About Us</a></li> -->
                    <li class="list-inline-item text-white h3 font-secondary @@nasted"></li>
                    </ul>
                    <!-- <p class="text-lighten">Our courses offer a good compromise between the continuous assessment favoured by some universities and the emphasis placed on final exams by others.</p> -->
                </div>
                </div>
            </div>
            </section>
        <!-- /page title -->
        <section class="section">
            <div class="container">
                <div class="row">
                    <loading-spinner v-if="isLoading"></loading-spinner>
                    <div v-else>
                        <div class="col-12">
                            <h2 class="section-title">FAQs</h2>
                            <div class="faq-accordion">
                                <div v-for="(category, index) in this.faqs" :key="index">
                                    <div class="category" @click="toggleCategory(index)">
                                        <h5>{{ category.faq_category_trans.name }}</h5>
                                    </div>
                                    <div v-if="selectedCategory === category" class="panel">
                                        <div v-for="(qa, idx) in category.faqs" :key="idx">
                                            <h6>{{ qa.faq_trans.question }}</h6>
                                            <p>{{ qa.faq_trans.answer }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            <!-- </div> -->
            </section>
  </template>

<script>
export default {
    data(){
        return {
            isLoading: true,
            faqs: [],
            selectedCategory: null,
        }
    },
    created(){

    },
    mounted(){
        this.getFaq();

    },
    methods: {
        toggleCategory(index) {
            this.selectedCategory = this.selectedCategory === this.faqs[index] ? null : this.faqs[index];
        },
        getFaq() {
            this.$axios.get('get-faqs', {
                crossdomain: true
            })
            .then(response => {
                if(response.data.status == 200){
                    this.isLoading = false;
                    this.faqs = response.data.data;
                }
            })
            .catch(error => {
                console.error(error);
            });
        },
    }
}
</script>
<style>
    @import url("https://fonts.googleapis.com/css2?family=Sora:wght@100;200;300;400;500;600;700&display=swap");
body {
    background: #fafafa;
}
.faq-accordion {
  width: 100%;
}

.category {
  background-color: #f1f1f1;
  padding: 10px;
  margin: 5px 0;
  cursor: pointer;
}

.category:hover {
  background-color: #ddd;
}

.panel {
  padding: 10px;
}

.qa-item {
  margin-bottom: 10px;
}

</style>


