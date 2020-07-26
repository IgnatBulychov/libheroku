<template>
    <div>
        <div v-if="isLoading" class="container">
            <div class="row">           
                <div v-for="sceleton in 6" :key="sceleton" class="col-12 col-sm-6 col-lg-4">
                    <div class="card card-default my-2 cover-card">
                            <div class="d-flex justify-content-center">
                                <div class="spinner-border m-5 text-primary" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>
                    </div> 
                </div>
            </div>
        </div>
        <div v-else-if="books.length" class="container">
            <div class="row">
                <div v-for="book in books" :key="book.id" class="col-12 col-sm-6 col-lg-4">
                    <div class="card card-default my-2 cover-card">
                        <div class="card-header text-center">
                            {{ book.title }}
                        </div>

                        <div class="card-body cover">
                            <img :src="book.cover">
                        </div>

                        <div class="card-footer text-center">
                            Теги:
                            <span v-for="tag in book.tags" :key="tag.id" class="mx-1 badge badge-primary">
                                {{ tag.title }}
                            </span> 
                        </div>

                        <div class="card-footer text-center">
                            <router-link class="btn btn-primary btn-sm" :to="`/books/${book.id}`">Подробнее</router-link>
                        </div>
                    </div>
                </div> 
            </div>
        </div>    
        <div v-else>        
            <a> По Вашему запросу ничего не найдено </a>
        </div>   
    </div>
</template>

<script>
export default {
    name: 'books-grid',
    computed: {
        books() {
            if (this.$store.getters.paginate) {
                return this.$store.getters.paginate.data;
            }
            return [];
        },
        isLoading() {
            return this.$store.getters.isLoading;
        }
    }    
}
</script>

<style scoped>
.cover {
    overflow: hidden;
}
img {
    width: 100%;
    object-fit: cover;
}
.cover-card .card-body {
    padding: 0;
    margin: 0;
}
</style>