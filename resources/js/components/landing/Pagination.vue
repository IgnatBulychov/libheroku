<template>
    <nav>
        <ul v-if="books.length" class="pagination">
            <li v-bind:class="[paginate.current_page == 1 ? 'disabled' : '', 'page-item']">
                <button class="page-link" @click="prevPage" aria-label="Previous" :disabled="isLoading">
                    <span aria-hidden="true">&laquo;</span>
                </button>
            </li>
            <li v-for="page in paginate.last_page" :key="page" v-bind:class="[paginate.current_page == page ? 'active' : '', 'page-item']">
                <button class="page-link" @click="goToPage(page)" :disabled="isLoading">
                    {{ page }}
                </button>
            </li>
            <li v-bind:class="[paginate.current_page == paginate.last_page ? 'disabled' : '', 'page-item']">
                <button class="page-link" @click="nextPage" aria-label="Next" :disabled="isLoading">
                    <span aria-hidden="true">&raquo;</span>
                </button>
            </li>
        </ul>
    </nav>
</template>

<script>
export default {
    name: 'pagnation',
    computed: {
            paginate() {
                return this.$store.getters.paginate;
            },
            isLoading() {
                return this.$store.getters.isLoading;
            },
            books() {
                if (this.$store.getters.paginate) {
                    return this.$store.getters.paginate.data;
                }
                return [];      
            } 
    },
    created() {  
        this.$store.dispatch('getPaginate', 'api' + this.$route.fullPath)      
    },
    watch: {
        '$route' (to, from) {            
            this.$store.dispatch('getPaginate', 'api' + this.$route.fullPath);
        },
    },
    methods: {
        goToPage(id) {
            if (this.$route.query.page != id) {
                this.$router.push({
                    path: '/'  + this.$route.params.filter,
                    query: {
                            tags: this.$route.query.tags,
                            search: this.$route.query.search,
                            page: id
                        }
                })
            }            
        },
        nextPage() {     
            this.$router.push({
                path: '/'  + this.$route.params.filter,
                query: {
                    tags: this.$route.query.tags,
                    search: this.$route.query.search,
                    page: parseInt(this.$route.query.page) + 1
                   
                }
            })
        },
        prevPage() {
             this.$router.push({
                path: '/'  + this.$route.params.filter,
                query: {
                    tags: this.$route.query.tags,
                    search: this.$route.query.search,
                    page: parseInt(this.$route.query.page) - 1
                   
                }
            })
        }
    }
}
</script>