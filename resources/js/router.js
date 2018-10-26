import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(VueRouter);

const router = new VueRouter({
    routes: [
        {
            path: '/',
            component: require('./components/the-dashboard.vue'),
        },
        {
            path: '/sites/create',
            name: 'sites.create',
            component: require('./components/sites.create.vue'),
        },
        {
            path: '/sites/:id',
            name: 'sites.show',
            component: require('./components/sites-show.vue'),
        },

        {
            path: '/databases',
            name: 'databases.index',
            component: require('./components/databases.index.vue'),
        },
        {
            path: '/databases/create',
            name: 'databases.create',
            component: require('./components/databases.create.vue'),
        },
    ]
});

export default router;
