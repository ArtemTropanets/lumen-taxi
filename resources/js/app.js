import 'bootstrap';
import Vue from 'vue';
import App from './App.vue';
import router from './router';

import axios from 'axios';
window.axios = axios;

new Vue({
    router,
    render: h => h(App),
}).$mount('#app')
