<template>
    <section class="page-title-section overlay" :style="{ backgroundImage:'url(front/theme_1/images/backgrounds/page-title.jpg)'}">
    </section>
    <section class="section">
        <div class="container">
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
            <loading-spinner v-if="isLoading"></loading-spinner>
            <template v-else>
                <div class="row">
                    <div class="col-12">
                        <h2 class="section-title">{{$t('Standout List')}}</h2>
                        <table class="table" v-if="this?.listData?.data?.length">
                             <caption class="d-none">listData</caption>
                            <thead>
                                <tr>
                                    <th>
                                        <a href="javascript:void(0);" @click="setSorting('image',(this.sortBy == 'image' && this.orderBy == 'desc') ? 'asc' : 'desc')">
                                        {{ $t('Image') }}</a>
                                        <i :class="'fas ' + fields['image']"></i>
                                    </th>
                                    <th>
                                        <a href="javascript:void(0);" @click="setSorting('name',(this.sortBy == 'name' && this.orderBy == 'desc') ? 'asc' : 'desc')">
                                        {{ $t('Name') }}</a>
                                        <i :class="'fas ' + fields['name']"></i>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in this?.listData?.data" :key="item?.id" >
                                    <td><img :alt="item?.name" v-lazy="item.image"  width="80" /></td>
                                    <td>{{item?.name}}</td>
                                </tr>
                            </tbody>
                        </table>
                        <table v-else>
                            <caption class="d-none">listData</caption>
                            <thead>
                                <th>
                                    <td colspan="2">No record found</td>
                                </th>
                            </thead>
                        </table>
                        <pagination  v-if="this.listData.data.length"
                        :current-page="listData.meta.current_page"
                        :last-page="listData.meta.last_page"
                        :change-page="setPaging"
                        />
                    </div>
                </div>
            </template>
        </div>
    </section>
</template>
<script>
    import { defineAsyncComponent } from 'vue';
    import { setGeneralQuerySearch,getPageLoadGeneralSearch,setGeneralSorting,setGeneralPaging,setGeneralSearch } from '@/utils.js';
    import { useAppStore } from '@/store'
    export default {
        components: {
            Pagination: defineAsyncComponent(() =>
              import('./include/Pagination.vue')
            ),
        },
        computed: { //pGupta
            locale() {
                return useAppStore().locale;
            },
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
                this.$axios.get('getStandoutList', {
                    params: {
                        page: this.page,
                        sortBy:this.sortBy,
                        orderBy:this.orderBy,
                        keyword:this.keyword
                    },
                })
                .then(async (response) => {
                    if (response.data.status == 200) {
                        this.isLoading = false;
                        this.listData = response.data.data;
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


