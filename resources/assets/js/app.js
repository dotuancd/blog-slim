
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

Vue.use(VueRouter);
Vue.use(Session, {storage: sessionStorage});

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// Vue.component('example', require('./components/Example.vue'));
// Vue.component('posts-index', );
// Vue.component('post', );
import {routes} from './routes';
// Routes
const router = new VueRouter({
    // mode: "history",
    routes
});

router.beforeEach((to, from, next) => {
    document.title = to.meta.title || 'Boom\'s blog'
    next()
});

const app = new Vue({
    router: router,
}).$mount('#app');
