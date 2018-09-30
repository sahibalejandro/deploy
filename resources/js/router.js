import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(VueRouter);

const router = new VueRouter({
    routes: [
        {
            path: '/',
            component: require('./components/the-dashboard.vue')
        },
        {
            path: '/sites/:id',
            name: 'sites.show',
            component: require('./components/sites-show.vue'),
        }
    ]
});

export default router;
