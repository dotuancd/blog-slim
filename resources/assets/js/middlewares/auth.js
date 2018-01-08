import Vue from 'vue';

export default (to, from, next) => {
    if (!Vue.Session.has('user')) {
        return next('/login');
    }
    let user = Vue.Session.get('user');
    axios.defaults.headers.common['Authorization'] = 'Bearer ' + user.api_token;
    next();
}