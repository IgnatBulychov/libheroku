<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col">
                <div class="card card-default">
                    <div class="card-header">Веселая книга</div>
                    <div class="card-body">
                        <div v-if="book" class="container">
                            <div class="row">
                                <div class="col-6">
                                    <dl class="row">
                                        <dt class="col-4">Название:</dt>
                                        <dd class="col-8">
                                            {{ book.title }}
                                        </dd>
                                        <dt class="col-4">Авторы:</dt>
                                        <dd class="col-8">
                                            {{ book.authors }}
                                        </dd>
                                        <dt class="col-4">Год издания:</dt>
                                        <dd class="col-8">
                                            {{ book.year }}
                                        </dd>
                                        <dt class="col-4">Теги</dt>
                                        <dd class="col-8">
                                            <span v-for="tag in book.tags" :key="tag.id" class="mx-1 badge badge-primary">
                                                {{ tag.title }}
                                            </span>
                                        </dd> 
                                    </dl>
                                </div>
                                <div class="col-6">
                                    <img :src="book.cover" class="rounded cover">                                     
                                </div>
                                    <button @click="back" class="btn btn-primary">Назад</button>                   
                                </div>
                            </div> 
                        </div>                       
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'book-view',
    created() {
        if (this.$store.getters.paginate) {
            this.book = this.$store.getters.paginate.data.find((book) => book.id == this.$route.params.id);
            return;
        }
        this.$store.isLoading = true;
        axios.get(`/api/books/${this.$route.params.id}`)
            .then((response) => {
                this.book = response.data.book;
                this.$store.isLoading = false;
        });
    },
    data() {
        return {
            book: null,
        };
    },
    computed: {

    },
    methods: { 
        back() {
            this.$router.go(-1);
        } 
    }
}
</script>

<style>
.cover {
    width: 100%;
}
</style>