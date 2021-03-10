import Vue from 'vue';
import Router from 'vue-router';
import eventBus from "./eventBus";

import PersonService from "./services/PersonService";
import RouteService from "./services/RouteService";

Vue.use(Router);

export default new Router({
    mode: 'history',
    routes: [
        {
            path: '/',
            name: 'home',
            component: () => import('./views/DailyAddressList'),
            props: true,
            beforeEnter(to, from, next) {
                setTimeout(() => {
                    eventBus.$emit('show-loader');
                    PersonService.getPersons()
                        .then(response => {
                            to.params.personsProp = response.data.persons;
                            next();
                        })
                        .catch(error => {
                            console.dir(error);
                            alert('Error');
                        })
                        .finally(() => eventBus.$emit('show-loader', false));
                });
            },
        },

        {
            path: '/routes',
            name: 'editRoutes',
            component: () => import('./views/EditRoutes'),
            props: true,
            beforeEnter(to, from, next) {
                setTimeout(() => {
                    eventBus.$emit('show-loader');
                    RouteService.getRoutesForEdit()
                        .then(response => {
                            to.params.routesProp = response.data;
                            next();
                        })
                        .catch(error => {
                            console.dir(error);
                            alert('Error');
                        })
                        .finally(() => eventBus.$emit('show-loader', false));
                });
            },
        },

        {
            path: '/persons',
            name: 'persons',
            component: () => import('./views/Persons'),
        },




        {
            path: '*',
            component: () => import('./views/NotFoundComponent'),
        },
    ]
});
