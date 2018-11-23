import './bootstrap';

import Vue from 'vue';
import PortalVue from 'portal-vue';
import router from './router';
import store from './store';
import './vue-directives';
import './vue-global-mixin';

import TheHeader from './components/the-header.vue';
import TheFooter from './components/the-footer.vue';
import AlertMessage from './components/alert-message.vue';

Vue.use(PortalVue);

const app = new Vue({
    store,
    router,
    el: '#app',
    components: {TheHeader, TheFooter, AlertMessage},
});
