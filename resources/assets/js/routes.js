import Index from './components/index.vue';
import Login from './components/auth/login.vue';
import Posts from './components/posts/index.vue';
import EditPost from './components/posts/edit.vue';
import SinglePost from './components/posts/show.vue';
import Register from './components/auth/register.vue';
import Comments from './components/comments/index.vue';
import CreatePost from './components/posts/create.vue';
import ChangePassword from './components/auth/change-password';
import auth from './middlewares/auth';
import RedirectIfAuthorized from './middlewares/redirect-if-authorized'

import PageNotFound from './components/errors/page-not-found.vue';

let routes = [
    {path: '/', name: 'index', component: Index, meta: {title: 'Boom\'s blog'}},
    {path: '/posts', name: 'posts.index', component: Posts, beforeEnter: auth},
    {path: '/posts/:post/comments', name: 'posts.comments', component: Comments, beforeEnter: auth},
    {path: '/posts/create', name: 'posts.create', component: CreatePost, beforeEnter: auth},
    {path: '/posts/:slug', name: 'posts.show', component: SinglePost},
    {path: '/posts/:post/edit', name: 'posts.edit', component: EditPost, beforeEnter: auth},
    {path: '/login', name: 'login', component: Login, beforeEnter: RedirectIfAuthorized},
    {path: '/register', name: 'register', component: Register, beforeEnter: RedirectIfAuthorized},
    {path: '/404', component: PageNotFound, name: 'errors.404'},
    {path: '/me/change-password', component: ChangePassword, name: 'me.changes-password', beforeEnter: auth}
];

export {
    routes
};