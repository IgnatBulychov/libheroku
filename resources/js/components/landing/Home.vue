<template>

</template>

<script>
    export default {
        name: 'home',
        created() {
            // сброс поиска и тегов
            this.$store.commit('setSelectedTags', []);
            this.$store.commit('setSearchQuery', '');
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
</script>

