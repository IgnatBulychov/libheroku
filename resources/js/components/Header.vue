<template>
    <nav class="navbar">
        <div class="container">
            <router-link class="navbar-brand" to="/">Веселая книга</router-link>
            <template v-if="!currentUser">
                <router-link to="/login" class="nav-link">Войти</router-link>
            </template>
            <template v-else>                    
                <router-link to="/dashboard/books" class="nav-link">Редактирование книг</router-link>
                <router-link to="/dashboard/tags" class="nav-link">Управление тегами</router-link>
                <a href="#!" @click.prevent="logout">Выход</a>                          
            </template>
        </div>
    </nav>
</template>

<script>
    export default {
        name: 'app-header',
        methods: {
            logout() {
                this.$store.commit('logout');
                this.$router.push('/');
            }
        },
        computed: {
            currentUser() {
                return this.$store.getters.currentUser
            }
        }
    }
</script>