window.bootstrap = require('bootstrap');
import Vue from 'vue';
import App from './App.vue';
import router from './router';
import eventBus from "./eventBus";

import axios from 'axios';
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

Object.defineProperty(Vue.prototype, '$eventBus', { value: eventBus });

new Vue({
    router,
    render: h => h(App),
}).$mount('#app');

import 'leaflet/dist/leaflet.css';
import 'leaflet-routing-machine/dist/leaflet-routing-machine.css';
