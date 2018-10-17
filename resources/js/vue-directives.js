/**
 * Use this file to register all Vue global directives.
 */

import Vue from 'vue';

Vue.directive('focus', {
    inserted(el) {
        el.focus();
    }
});
