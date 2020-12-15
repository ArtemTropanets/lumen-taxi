import Vue from 'vue';
import Router from 'vue-router';

Vue.use(Router);

export default new Router({
    mode: 'history',
    routes: [
        {
            path: '/',
            name: 'daily-address-list',
            component: () => import('./views/DailyAddressList'),
        },




        {
            path: '*',
            component: () => import('./views/NotFoundComponent'),
        },
    ]
});
