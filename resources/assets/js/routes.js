import Index from './components/index.vue';
import Posts from './components/posts/index.vue';
import SinglePost from './components/posts/show.vue';
import CreatePost from './components/posts/create.vue';
import EditPost from './components/posts/edit.vue';
import Login from './components/auth/login.vue';
import auth from './middlewares/auth';
import PageNotFound from './components/errors/page-not-found.vue';

let routes = [
    {path: '/', name: 'index', component: Index, meta: {title: 'Boom\'s blog'}},
    {path: '/posts', name: 'posts.index', component: Posts},
    {path: '/posts/create', name: 'posts.create', component: CreatePost, beforeEnter: auth},
    {path: '/posts/:post', name: 'post.show', component: SinglePost},
    {path: '/posts/:post/edit', name: 'post.edit', component: EditPost, beforeEnter: auth},
    {path: '/login', component: Login},
    {path: '/404', component: PageNotFound, name: 'errors.404'}
];

export {
    routes
};