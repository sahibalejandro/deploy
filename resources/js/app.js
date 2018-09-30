import './bootstrap';

import Vue from 'vue';
import router from './router';

import TheHeader from './components/the-header.vue';
import TheFooter from './components/the-footer.vue';
import AlertMessage from './components/alert-message.vue';

const app = new Vue({
    el: '#app',
    components: {TheHeader, TheFooter, AlertMessage},
    router
});
