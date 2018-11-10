import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        alertMessages: []
    },

    mutations: {
        alert({alertMessages}, message) {
            alertMessages.push(message);
            setTimeout(() => alertMessages.shift(), 5000);
        },
    },
});
