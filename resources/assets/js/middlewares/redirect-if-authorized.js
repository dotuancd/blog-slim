import Vue from 'vue';

export default (to, from, next) => {
    if (Vue.$session.has('user')) {
        return next('/');
    }

    return next();
}