window.bootstrap = require('bootstrap');
import Vue from 'vue';
import App from './App.vue';
import router from './router';

import axios from 'axios';
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

Object.defineProperty(Vue.prototype, '$eventBus', { value: new Vue() });

new Vue({
    router,
    render: h => h(App),
}).$mount('#app');

import 'leaflet/dist/leaflet.css';
