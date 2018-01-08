
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
import Index from './components/index.vue';
import Posts from './components/posts/index.vue';
import SinglePost from './components/posts/show.vue';
import CreatePost from './components/posts/create.vue';
import EditPost from './components/posts/edit.vue';
import Login from './components/auth/login.vue';
import auth from './middlewares/auth';

// Routes
const routes = [
    {path: '/', name: 'index', component: Index},
    {path: '/posts', name: 'posts.index', component: Posts},
    {path: '/posts/create', name: 'posts.create', component: CreatePost, beforeEnter: auth},
    {path: '/posts/:post', name: 'post.show', component: SinglePost},
    {path: '/posts/:post/edit', name: 'post.edit', component: EditPost, beforeEnter: auth},
    {path: '/login', component: Login}
];

const router = new VueRouter({
    // mode: "history",
    routes: routes
});

const app = new Vue({
    router: router
}).$mount('#app');
