<template>
    <div>
        <fieldset :disabled="isLoading">
            <form @submit.prevent="update" class="my-3">
                <div class="form-group row">
                    <label for="title" class="col-sm-2 col-form-label">Название</label>
                    <div class="col-sm-10">
                        <input v-model="book.title" placeholder="Название" type="text" class="form-control" id="title">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="coverIsNotChanging" class="col-sm-2 col-form-label">Изменить обложку</label>
                    <div class="col-sm-10">
                        <input v-model="coverIsChanging" id="coverIsChanging" type="checkbox">
                    </div>    
                </div>
                <div  v-if="!coverIsChanging" class="form-group row">
                    <label for="coverImg" class="col-sm-2 col-form-label"></label>
                    <div class="col-sm-10">
                        <img :src="book.cover" class="cover">
                    </div>                
                </div>
                <div v-if="coverIsChanging" class="form-group row">
                    <label for="cover" class="col-sm-2 col-form-label">Обложка</label>
                    <div class="col-sm-10">
                        <input @change="onFileChange" type="file" id="cover" class="form-control-file">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="authors" class="col-sm-2 col-form-label">Авторы</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" v-model="book.authors" id="authors" placeholder="Авторы"/>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="year" class="col-sm-2 col-form-label">Год издания</label>
                    <div class="col-sm-10">
                        <input type="number" class="form-control" v-model="book.year" id="year" placeholder="Год издания"/>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-2">Теги</div>
                    <div class="col-sm-10">
                        <div v-for="tag in tags" :key="tag.id" class="form-check">
                            <input v-model="selectedTags" class="form-check-input" :id="'defaultCheck' + tag.id" type="checkbox" :value="tag.id">
                            <label class="form-check-label" :for="'defaultCheck' + tag.id">
                                {{tag.title}}
                            </label>
                        </div>
                    </div>
                </div>
                <router-link to="/dashboard/books" class="btn btn-secondary">Отмена</router-link>                   
                <button type="submit" class="btn btn-primary">Изменить</button>
            </form>  
        </fieldset> 
        <server-errors :errors="serverErrors"></server-errors>    
    </div>
</template>

<script>
    import serverErrors from '../../serverErrors.vue';
    export default {
        name: 'update',
        components: {
            serverErrors
        },
        created() {
            // для оптимизации, чтобы не делать лишних запросов в БД, если список уже загружен
            if (this.books.length) {
                this.book = this.books.find((book) => book.id == this.$route.params.id);
                this.selectedTagsInit(this.book);
            } else {
                this.$store.commit('setLoading', true);
                axios.get(`/api/books/${this.$route.params.id}`)
                .then((response) => {
                    this.book = response.data.book;
                    this.selectedTagsInit(this.book);
                    this.$store.commit('setLoading', false);
                });
            }
            if (!this.tags) {
                this.$store.dispatch('getTags');
            }
            
        },
        data() {
            return {
                book: {
                    title: '',
                    cover: '',
                    authors: '',
                    year: ''
                },                
                cover: null,
                selectedTags: [],
                coverIsChanging: false
            };
        },
        computed: {
            currentUser() {
                return this.$store.getters.currentUser;
            },
            books() {
                return this.$store.getters.books;
            },
            tags() {
                return this.$store.getters.tags;
            },
            isLoading() {
                return this.$store.getters.isLoading;
            },
            serverErrors() {
                return this.$store.getters.serverErrors;
            }            
        },
        methods: {
            update() {
                let app = this;
                const formData = new FormData();
                formData.append("book", JSON.stringify(this.book));
                formData.append("tags", JSON.stringify(this.selectedTags));
                formData.append("coverIsChanging", this.coverIsChanging ? 1 : 0);
                if (app.cover) formData.append("cover", app.cover);
                app.$store.dispatch('updateBook', [ formData, this.book.id ]);
            },
            onFileChange(e) {
                let files = e.target.files || e.dataTransfer.files;
                if (!files.length)
                    return;
                this.cover = files[0];
            },
            selectedTagsInit(book) {
                let tags = [];
                book.tags.forEach(tag => {
                    tags.push(tag.id);
                });
                this.selectedTags = tags;
            }            
        }
    }
</script>

<style>
.cover {
    height: 150px;
    width: auto;
}
</style>