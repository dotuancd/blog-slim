import Vue from 'vue';

export default (to, from, next) => {
    if (!Vue.$session.has('user')) {
        Vue.$flash.info('Sign in to continue to admin area');
        return next('/admin');
    }
    return next();
}