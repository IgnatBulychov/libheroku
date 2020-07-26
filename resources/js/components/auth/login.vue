<template>
    <div class="container">
        <div class="login row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Вход</div>
                    <div class="card-body">
                        <fieldset :disabled="isLoading">
                            <form @submit.prevent="authenticate" class="mx-4">
                                <div class="form-group row">
                                    <label for="email">Email:</label>
                                    <input type="email" v-model="form.email" placeholder="Email" class="form-control">
                                </div>
                                <div class="form-group row">
                                    <label for="password">Пароль:</label>
                                    <input type="password" v-model="form.password" class="form-control" placeholder="Пароль">
                                </div>
                                <div class="form-group row">
                                    <input class="btn btn-primary" type="submit" value="Войти">
                                </div>
                                <div class="form-group row" v-if="authError">
                                    <div class="alert alert-danger" role="alert">
                                        {{ authError }}
                                    </div>
                                </div>
                            </form>
                        </fieldset>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {login} from '../../helpers/auth';
    export default {
        name: "login",
        data() {
            return {
                form: {
                    email: '',
                    password: ''
                },
                error: null
            };
        },
        methods: {
            authenticate() {
                this.$store.dispatch('login');
                login(this.$data.form)
                .then((res) => {
                    this.$store.commit("loginSuccess", res);
                    this.$router.push('/dashboard/books');
                })
                .catch((error) => {
                    this.$store.commit("loginFailed", {error});
                });
            }
        },
        computed: {
            authError() {
                return this.$store.getters.authError;
            },
            isLoading() {
                return this.$store.getters.isLoading;
            }
        }
    }
</script>

<style scoped>
.error {
    text-align: center;
    color: red;
}
</style>
