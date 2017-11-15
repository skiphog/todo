import Vue from 'vue';

import VueRouter from 'vue-router';

import Register from '../views/auth/register.vue';
import Login from '../views/auth/login.vue';
import Dash from '../views/dashboard.vue';
import Form from '../views/form.vue';

Vue.use(VueRouter);

const router = new VueRouter({
  mode: 'history',
  routes: [
    {path: '/dashboard/:id/edit', component: Form, meta: {mode: 'edit'}},
    {path: '/dashboard/create', component: Form, meta: {mode: 'update'}},
    {path: '/register', component: Register},
    {path: '/login', component: Login},
    {path: '/dashboard', component: Dash}
  ]
});

export default router;