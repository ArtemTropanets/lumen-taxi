import Vue from 'vue';
import Router from 'vue-router';

Vue.use(Router);

export default new Router({
    mode: 'history',
    routes: [
        {
            path: '/',
            name: 'home',
            component: () => import('./views/DailyAddressList'),
        },

        {
            path: '/edit-routes',
            name: 'editRoutes',
            component: () => import('./views/EditRoutes'),
        },

        {
            path: '/all-persons',
            name: 'allPersons',
            component: () => import('./views/AllPersons'),
        },




        {
            path: '*',
            component: () => import('./views/NotFoundComponent'),
        },
    ]
});
