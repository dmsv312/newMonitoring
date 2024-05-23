import {createStore} from "vuex"

export default createStore({
    state: {
        token: '',
        isAuth: false,
        role: '',
    },
    getters: {

    },
    mutations: {
        setToken(state, token) {
            state.token = token;
        },
        setIsAuth(state, isAuth) {
            state.isAuth = isAuth
        },
        setRole(state, role) {
            state.role = role
        },
    },
    actions: {

    },
    modules: {

    }
})