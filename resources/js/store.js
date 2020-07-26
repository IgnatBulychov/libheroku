import { getLocalUser } from "./helpers/auth";
import { router } from './app';

const user = getLocalUser();

export default {
    state: {
        currentUser: user,
        isLoggedIn: !!user,
        loading: false,
        auth_error: null,
        paginate: null,
        books: [],
        tags: [],
        selectedTags: [],
        searchQuery: '',
        serverErrors: []
    },
    getters: {
        isLoading(state) {
            return state.loading;
        },
        isLoggedIn(state) {
            return state.isLoggedIn;
        },
        currentUser(state) {
            return state.currentUser;
        },
        authError(state) {
            return state.auth_error;
        },
        paginate(state) {
            return state.paginate;
        },
        books(state) {
            return state.books;
        },
        tags(state) {
            return state.tags;
        },
        selectedTags(state) {
            return state.selectedTags;
        },
        searchQuery(state) {
            return state.searchQuery;
        },
        serverErrors(state) {
            return state.serverErrors
        }     
    },
    mutations: {
        login(state) {
            state.loading = true;
            state.auth_error = null;
        },
        loginSuccess(state, payload) {
            state.auth_error = null;
            state.isLoggedIn = true;
            state.loading = false;
            state.currentUser = Object.assign({}, payload.user, {token: payload.access_token});
            localStorage.setItem("user", JSON.stringify(state.currentUser));
        },
        loginFailed(state, payload) {
            state.loading = false;
            state.auth_error = payload.error;
        },
        logout(state) {
            localStorage.removeItem("user");
            state.isLoggedIn = false;
            state.currentUser = null;
        },
        setLoading(state, boolean) {
            state.loading = boolean;
        },
        updatePaginate(state, payload) {
            state.paginate = payload;
        },
        updateBooks(state, payload) {
            state.books = payload;
        },
        addBook(state, payload) {
            state.books.push(payload);
        },
        updateBook(state, payload) {
            state.books.forEach(function(item, index, array) {              
                if (payload.id == item.id) {
                    array.splice(index, 1, payload);
                }               
            });
        },
        removeBook(state, id) {
            state.books.forEach(function(item, index, array) {              
                if (id == item.id) {
                    array.splice(index, 1);
                }               
            });
        },
        updateTags(state, payload) {
            state.tags = payload;
        },
        addTag(state, payload) {
            state.tags.push(payload);
        },
        removeTag(state, id) {
            state.tags.forEach(function(item, index, array) {              
                if (id == item.id) {
                    array.splice(index, 1);
                }               
            });
        },
        setSelectedTags(state, payload) {
            state.selectedTags = payload
        },
        setSearchQuery(state, payload) {
            state.searchQuery = payload;
        },
        setErrors(state, errors) {
            state.serverErrors = errors
        }
    },
    actions: {
        login(context) {
            context.commit("login");
        },
        getPaginate(context, url) {
           context.commit('setLoading', true);
            axios.get(url)
            .then((response) => {
                context.commit('setLoading', false);                
                context.commit('updatePaginate', response.data.paginate);
            })
        },
        getBooks(context) {
            context.commit('setLoading', true);
            axios.get('/api/books')
            .then((response) => {
                context.commit('setLoading', false);
                context.commit('updateBooks', response.data.books);
            })
        },
        createBook(context, formData) {
            context.commit('setLoading', true);
            context.commit('setErrors', []);
            axios.post('/api/books/new', formData)
            .then((response) => {
                if (response.data.status == "error") {
                    context.commit('setLoading', false);
                    context.commit('setErrors', response.data.errors);
                    return;
                }
                context.commit('setLoading', false);
                context.commit('addBook', response.data.book);
                router.push('/dashboard/books');
            })
        },
        updateBook(context, [ formData, id ]) {
            context.commit('setLoading', true);
            context.commit('setErrors', []);
            axios.post('/api/books/update/' + id, formData)
            .then((response) => {
                if (response.data.status == "error") {
                    context.commit('setLoading', false);
                    context.commit('setErrors', response.data.errors);
                    return;
                }
                context.commit('setLoading', false);
                context.commit('updateBook', response.data.book);
                router.push('/dashboard/books');
            })
        },
        removeBook(context, id) {
            context.commit('setLoading', true);
            axios.delete('/api/books/remove/' + id)
            .then((response) => {
                context.commit('setLoading', false);
                context.commit('removeBook', id);
            })
        },        
        getTags(context) {
            context.commit('setLoading', true);
            axios.get('/api/tags')
            .then((response) => {
                context.commit('setLoading', false);
                context.commit('updateTags', response.data.tags);   
            })
        },
        createTag(context, tag) {
            context.commit('setLoading', true);
            axios.post('/api/tags/new', tag)
            .then((response) => {                
                context.commit('setLoading', false);
                context.commit('addTag', response.data.tag);
            }) 
        },
        removeTag(context, id) {
            context.commit('setLoading', true);
            axios.delete('/api/tags/remove/' + id)
            .then((response) => {
                context.commit('setLoading', false);
                context.commit('removeTag', id);      
            })
        }
    }
};