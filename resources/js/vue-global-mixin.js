import Vue from 'vue';

Vue.mixin({
    methods: {
        alert(text, type = 'success') {
            this.$store.commit('alert', {text, type});
        }
    }
});
