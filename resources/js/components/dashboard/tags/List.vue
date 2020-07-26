<template>
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="card card-default">
                    <div class="card-header">Управление тегами</div>
                    <div class="card-body">
                        <div class="btn-wrapper">
                            <fieldset :disabled="isLoading">
                                <form @submit.prevent="add">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col">
                                                <input type="text" class="form-control" v-model="tag.title" placeholder="Название тега" required/>
                                            </div>
                                    
                                            <div class="col">
                                                <button type="submit" class="btn btn-primary btn-sm">Создать тег</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </fieldset>
                        </div>
                        <table class="table">
                            <thead>
                                <th>Название</th>
                                <th>Действия</th>
                            </thead>
                            <tbody>
                                <template v-if="!tags.length">
                                    <tr>
                                        <td colspan="6" class="text-center">Нет тегов</td>
                                    </tr>
                                </template>
                                <template v-else>
                                    <tr v-for="tag in tags" :key="tag.id">
                                        <td>{{ tag.title }}</td>
                                        <td>
                                            <button class="btn btn-danger" @click="remove(tag.id)"  :disabled="isLoading">Удалить</button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>    
</template>

<script>
    export default {
        name: 'list',
        data() {
            return {
                tag: {
                    title: ''
                }
            };
        },
        methods: {
            add() {
                const formData = new FormData();
                formData.append("tag", JSON.stringify(this.tag));
                this.$store.dispatch('createTag', formData);                          
            },
            remove(id) {
                this.$store.dispatch('removeTag', id);               
            }
        },
        mounted() {
            if (this.tags.length) {
                return;
            }            
            this.$store.dispatch('getTags');
        },
        computed: {
            tags() {
                return this.$store.getters.tags;
            },
            isLoading() {
                return this.$store.getters.isLoading;
            }
        }
    }
</script>

<style scoped>
.btn-wrapper {
    text-align: right;
    margin-bottom: 20px;
}
</style>