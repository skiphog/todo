import Vue from 'vue';

import VueRouter from 'vue-router';

import Form from '../views/form.vue';
import Index from '../views/index.vue';
import Dash from '../views/dashboard.vue';
import Login from '../views/auth/login.vue';
import NotFound from '../views/not-found.vue';
import Register from '../views/auth/register.vue';

Vue.use(VueRouter);

const router = new VueRouter({
  mode: 'history',
  linkActiveClass: 'uk-active',
  routes: [
    {path: '/', component: Index},
    {path: '/dashboard/:id/edit', component: Form, meta: {mode: 'edit'}},
    {path: '/dashboard/create', component: Form, meta: {mode: 'update'}},
    {path: '/register', component: Register},
    {path: '/login', component: Login},
    {path: '/dashboard', component: Dash},
    {path: '/not-found', component: NotFound},
    {path: '*', component: NotFound}
  ]
});

export default router;