<template>
    <section class="page-title-section overlay" :style="{ backgroundImage:'url(front/theme_1/images/backgrounds/page-title.jpg)'}">
        <div class="container">
        </div>
    </section>
    <loading-spinner v-if="isLoading"></loading-spinner>
    <template v-else>
        <main class="container mt-3" v-if="this?.listData?.blogCategories.length">
            <BlogCategories :blogCategories="this?.listData?.blogCategories"  />
            <div class="row">
                <div class="col-auto">
                    <input type="text" class="form-control" v-model="keywordText" placeholder="Enter Keyword">
                </div>
                <div class="col-auto">
                    <input type="button" class="btn btn-primary mb-3" @click="setSearch()" value="Search" />
                </div>
                <div class="col-auto" v-if="keyword">
                    <input type="button" class="btn btn-danger mb-3" @click="setSearch('reset')" value="Reset" />
                </div>
            </div>
        </main>
        <main class="container mt-3" v-if="this?.listData?.data?.length">
            <div class="mb-2 row g-0 border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative"
                v-for="item in this?.listData?.data" :key="item?.id" >
                <div class="col p-4 d-flex flex-column position-static">
                    <h3 class="mb-0">{{ item?.post_translation?.title }}</h3>
                    <div class="mb-1 mt-3  text-muted">
                        <strong class="d-inline-block mb-2 me-1 text-primary">{{(item?.author?.first_name)}} {{(item?.author?.last_name)}}</strong>
                        {{convertUTCInClientTz(item?.created_at)}}
                    </div>
                    <p class="card-text mb-auto"> {{showExcerpt(getText(item?.post_translation?.content),200)}}</p>
                    <router-link v-if="item?.post_translation?.slug" class="stretched-link  mt-3" :to="{ name: 'blog-details-lang', params: { slug: item?.post_translation?.slug }}">Continue Reading...</router-link>
                    <br/>
                </div>
                <div class="col-auto d-none d-lg-block mt-5">
                    <img class="bd-placeholder-img" :alt="item?.post_translation?.title" v-lazy="item.image"  width="400" />
                </div>
            </div>
            <pagination  v-if="this?.listData?.data?.length"
            :current-page="listData?.meta?.current_page"
            :last-page="listData?.meta?.last_page"
            :change-page="setPaging" />
        </main>
        <template v-else>
            <main class="container mt-3" >
                <div class="row">
                    <div class="col-12">
                        <table width="100%">
                            <caption class="d-none">listData</caption>
                            <thead>
                                <th>
                                    <td colspan="2" class="text-center">
                                        No record found
                                        <br/> <br/> <br/>
                                    </td>
                                </th>
                            </thead>
                        </table>
                    </div>
                </div>
            </main>
        </template>
    </template>
</template>
<script>

    import { defineAsyncComponent } from 'vue';
    import { setGeneralQuerySearch,getPageLoadGeneralSearch,setGeneralSorting,setGeneralPaging,setGeneralSearch,CommonMethods } from '@/utils.js';
    import { useAppStore } from '@/store'
    import { RouterLink } from 'vue-router';

    export default {
        mixins: [CommonMethods],
        components: {
            Pagination: defineAsyncComponent(() =>
              import('./include/Pagination.vue')
            ),
            BlogCategories: defineAsyncComponent(() =>
              import('./include/blog-categories.vue')
            ),
            RouterLink
        },
        watch: { //pGupta
            '$route'(to, from) {
                this.getList();
            }
        },
        computed: {
            locale() {
                 return useAppStore().locale;
            }
        },
        data(){
            return {
                isLoading: true,
                listData: {
                    data: [],
                    current_page: 1,
                    last_page: 1,
                },
                page:1,
                sortBy:'',
                orderBy:'',
                keyword:'',
                keywordText:'',
                fields:{'name':'fa-sort','image':'fa-sort'},
            }
        },
        created(){
            this.loadPage();
        },
        methods: {
            getText(value) {
                const div = document.createElement('div')
                div.innerHTML = value;
                return div.textContent || div.innerText || '';
            },
            async loadPage(){
                getPageLoadGeneralSearch(this);
                this.getList();
            },
            async setSearch(type='search') {
                setGeneralSearch(this,type)
                this.getList()
            },
            async getList() {
                this.isLoading = true;
                this.$axios.get('get-blogs', {
                    params: {
                        page: this.page,
                        sortBy:this.sortBy,
                        orderBy:this.orderBy,
                        keyword:this.keyword,
                        params: this.$route.params,
                        name: this.$route.name,
                    },
                })
                .then(async (response) => {
                    if (response.data.status == 200) {
                        this.isLoading = false;
                        this.listData = response.data.data;
                        console.log(this.listData.blogCategories);
                        setGeneralQuerySearch(this);
                    }
                })
                .catch((error) => {
                    console.error('Error fetching listData :', error);
                });
            },
            setSorting(sortBy,orderBy) {
                setGeneralSorting(this,sortBy,orderBy)
                this.getList()
            },
            setPaging(page) {
                setGeneralPaging(this,page);
                this.getList()
            },
        }
    }
</script>

