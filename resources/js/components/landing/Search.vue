<template>
    <div>
        <form @submit.prevent="getBySearch" class="form-inline">
            <input v-model="searchQuery" class="form-control mr-sm-2" type="search" placeholder="Поиск" aria-label="Search">
            <button class="btn btn-outline-primary my-2 my-sm-0" type="submit">Поиск</button>
        </form>
    </div>
</template>

<script>
export default {
    name: 'search',
    data() {
            return {

            };
    },
    created() {
        if (this.$route.query.search) {
            this.searchQuery = this.$route.query.search;
        }
    },
    computed: {
        searchQuery: {
            get: function () {
                return this.$store.getters.searchQuery;
            },
            set: function (newValue) {
                this.$store.commit('setSearchQuery', newValue);
            }
        },      
    },
    methods: {
        getBySearch() {
            // сбрасываем теги
            this.$store.commit('setSelectedTags', []);
            this.$store.commit('setLoading', true);
            if (this.searchQuery) {               
                if (this.searchQuery != this.$route.query.search) {
                    this.$router.push({
                        path: '/booksbysearch?search=' + this.searchQuery + '&page=1'           
                    });
                    this.searchQueryHaveBeenChanged = false;
                    this.$store.commit('setLoading', true);
                }
            } else {
                if (this.$route.fullPath != '/allbooks?page=1&tags&search') {
                     this.$router.push({
                    path: '/allbooks',
                    query: {
                        page: 1,
                        tags: null,
                        search: null
                    }
                    });
                }   
            }
            this.$store.commit('setLoading', false);
        }
    }
}
</script>
