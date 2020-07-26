import { setAuthorization } from "./general";

export function login(credentials) {
    return new Promise((res, rej) => {
        axios.post('/api/auth/login', credentials)
            .then((response) => {
                // пр успешном входе записываем в localstorage токен
                setAuthorization(response.data.access_token);
                res(response.data);
            })
            .catch((err) =>{
                rej("Неверный логин или пароль");
            })
    })
}

// возвращает пользователья из localstorage 

export function getLocalUser() {
    const userStr = localStorage.getItem("user");

    if(!userStr) {
        return null;
    }

    return JSON.parse(userStr);
}