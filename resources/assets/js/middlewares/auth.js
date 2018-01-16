import Vue from 'vue';

export default (to, from, next) => {
    if (!Vue.$session.has('user')) {
        Vue.$flash.info('Sign in to continue to admin area');
        return next('/admin');
    }
    let user = Vue.$session.get('user');
    window.axios.defaults.headers.common['Authorization'] = 'Bearer ' + user.api_token;
    return next();
}