<template>
    <div>
        <div class="btn-wrapper">
            <router-link to="/dashboard/books/new" class="btn btn-primary btn-sm">Создать книгу</router-link>
        </div>
        <table class="table">
            <thead>
                <th>Название</th>
                <th>Обложка</th>
                <th>Авторы</th>
                <th>Теги</th>
                <th>Год издания</th>
                <th>Действия</th>
            </thead>
            <tbody>
                <template v-if="!books.length">
                    <tr>
                        <td colspan="6" class="text-center">Нет книг</td>
                    </tr>
                </template>
                <template v-else>
                    <tr v-for="book in books" :key="book.id">
                        <td>{{ book.title }}</td>
                        <td>
                            <img :src="book.cover" class="cover">
                        </td>
                        <td>{{ book.authors }}</td>
                        <td>
                            <span v-for="tag in book.tags" :key="tag.id" class="badge badge-info">{{ tag.title }}</span>
                        </td>
                        <td>{{ book.year }}</td>
                        <td>
                            <button class="btn btn-primary" @click="update(book.id)" :disabled="isLoading">Редактировать</button>
                            <button class="btn btn-danger" @click="remove(book.id)" :disabled="isLoading">Удалить</button>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        name: 'list',
        mounted() {
            if (this.books.length) {
                return;
            }            
            this.$store.dispatch('getBooks');
        },
        computed: {
            books() {
                return this.$store.getters.books;
            },
            isLoading() {
                return this.$store.getters.isLoading;
            }
        },
        methods: {
            update(id) {
                this.$router.push('/dashboard/books/' + id); // не сделал to="" т.к. у router-link нет нормального disabled атрибута            
            },
            remove(id) {
                this.$store.dispatch('removeBook', id);               
            }
        }
    }
</script>

<style scoped>
.btn-wrapper {
    text-align: right;
    margin-bottom: 20px;
}
.cover {
    height: 50px;
    width: auto;
}
</style>