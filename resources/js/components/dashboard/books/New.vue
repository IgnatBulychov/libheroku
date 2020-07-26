<template>
    <div>
        <fieldset :disabled="isLoading">
            <form @submit.prevent="add" class="my-3">
                <div class="form-group row">
                    <label for="title" class="col-sm-2 col-form-label">Название</label>
                    <div class="col-sm-10">
                        <input v-model="book.title" placeholder="Название" type="text" class="form-control" id="title">
                    </div>
                </div>
                <div class="form-group row">
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
                <button type="submit" class="btn btn-primary">Создать</button>
            </form>
        </fieldset>
        <server-errors :errors="serverErrors"></server-errors>    
    </div>
</template>

<script>
    import serverErrors from '../../serverErrors.vue';
    export default {
        name: 'new',
        components: {
            serverErrors
        },
        data() {
            return {
                book: {
                    title: '',
                    authors: '',
                    year: ''
                },
                cover: null,
                selectedTags: []
            };
        },
        created() {
            if (!this.$store.getters.tags.length) {
                this.$store.dispatch('getTags');
            }
        },
        computed: {
            currentUser() {
                return this.$store.getters.currentUser;
            },
            serverErrors() {
                return this.$store.getters.serverErrors;
            },
            isLoading() {
                return this.$store.getters.isLoading;
            },
            tags() {
                return this.$store.getters.tags;
            }            
        },
        methods: {
            add() {
                let app = this;
                
                const formData = new FormData();
                formData.append("book", JSON.stringify(this.book));
                formData.append("tags", JSON.stringify(this.selectedTags));
                if (app.cover) formData.append("cover", app.cover);
                app.$store.dispatch('createBook', formData);
            },
            onFileChange(e) {
                let files = e.target.files || e.dataTransfer.files;
                if (!files.length)
                    return;
                this.cover = files[0];
            }
        }
    }
</script>

<style>

</style>
