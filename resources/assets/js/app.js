
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

// window.Vue = require('vue');
import Vue from 'vue';
import VueRouter from 'vue-router';
import Session from './support/session';
import Flash from './support/flash'
import ErrorHandler from './support/error-handler'

import Navigation from './components/layout/navigation'
import EventBus from './support/events';

Vue.use(VueRouter);
Vue.use(EventBus);
Vue.use(Session, {storage: sessionStorage});
Vue.use(Flash)

// If user refresh page should set api token to request.
if (Vue.$session.has('user')) {
    let user = Vue.$session.get('user');
    window.axios.defaults.headers.common['Authorization'] = 'Bearer ' + user.api_token;
}

Vue.$events.$on('login', (user) => {
    window.axios.defaults.headers.common['Authorization'] = 'Bearer ' + user.api_token;
});

Vue.$events.$on('logout', () =>  {
    console.log('logout trigger');
    delete window.axios.defaults.headers.common['Authorization'];
})

// Vue.$events.$on('login', (user) => {
//     window.axios.defaults.headers.common['Authorization'] = 'Bearer ' + user.api_token;
// });

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('navigation', Navigation)

// Vue.component('example', require('./components/Example.vue'));
// Vue.component('posts-index', );
// Vue.component('post', );
import {routes} from './routes';
// Routes
const router = new VueRouter({
    // mode: "history",
    routes
});

Vue.use(ErrorHandler, {
    router: router
})

router.beforeEach((to, from, next) => {
    document.title = to.meta.title || 'Loading...'
    next()
});

const app = new Vue({
    router: router,
}).$mount('#app');
