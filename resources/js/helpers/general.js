export function initialize(store, router) {

    // проверяем при каждом маршруте зарегистрирован ли пользователь, т.е. есть ли инфа о нем в localstorage

    router.beforeEach((to, from, next) => {
        const requiresAuth = to.matched.some(record => record.meta.requiresAuth);
        const currentUser = store.state.currentUser;
    
        if(requiresAuth && !currentUser) {
            next('/login');
        } else if(to.path == '/login' && currentUser) {
            next('/');
        } else {
            next();
        }
    });

    // проверяем не закончилась ли сессия, если да, производим выход 
    
    axios.interceptors.response.use(null, (error) => {
        store.commit('logout');
        router.push('/');
        return Promise.reject(error);
    });

    // если пользователь авторизован добавляем к запросу токен 

    if (store.getters.currentUser) {
        setAuthorization(store.getters.currentUser.token);
    }
}

export function setAuthorization(token) {
    axios.defaults.headers.common["Authorization"] = `Bearer ${token}`
}