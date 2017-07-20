import Vue from 'vue';
import VueResource from 'vue-resource';
import lodash from 'lodash'
import VueLodash from 'vue-lodash/dist/vue-lodash.min'

import App from './App';
import CartButton from './components/CartButton';
import { currency } from './filters/currency';
import router from './router';
import store from './store'

Vue.use(VueResource);
Vue.use(VueLodash, lodash);
Vue.filter('currency', currency)

const app = new Vue({
    el: '#container',
    router,
    store,
    components: { App, CartButton },
    data() {
      return {
          showCrumbs: false,
          pageTitle: null,
      }
    },

    created() {
        this.$store.dispatch('getAllPlugins')
        this.$store.dispatch('getAllCategories')
        this.$store.dispatch('getStaffPicks')
    }
});
