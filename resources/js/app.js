/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
window.Vue = require('vue');
window.Events = new Vue();

import Toaster from 'v-toaster';
import 'v-toaster/dist/v-toaster.css';
import store from './store/index';
import { CHANGE_APP_LANG } from "./store/mutation-types";
import storage from './utils/storage';
import user from './utils/user';

store.commit(CHANGE_APP_LANG, window.getLocal);

// Plugins
Vue.use(Toaster, {timeout: 3000});


// Pages
Vue.component('categories-page', require('./pages/CatagoriesPage.vue').default);

// Components
// -- 'X' Components
// Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('favorite-btn', require('./components/FavoriteBtn.vue').default);
Vue.component('cart-list', require('./components/CartList.vue').default);
Vue.component('cart-btn', require('./components/CartBtn.vue').default);
Vue.component('cart-delete-btn', require('./components/CartDeleteBtn.vue').default);
Vue.component('cart-table', require('./components/CartTable.vue').default);
Vue.component('product-controls', require('./components/ProductControls.vue').default);
Vue.component('pagination', require('laravel-vue-pagination'));


// Prototype Inheritance
Vue.prototype.$trans = function (trans_key) {
    if(window.translation.hasOwnProperty(trans_key)) {
        return window.translation[trans_key];
    }

    return trans_key;
};

Vue.prototype.$isAuth = user.isAuth();

Vue.prototype.$isGuest = user.isGuest();

Vue.prototype.$user = user.user();

Vue.prototype.$storage = storage;


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
const app = new Vue({
    el: '#app',
    store,
    created() {
        if (this.$isAuth && this.$storage.has('products_in_cart')) {
            axios.post('/products/add-multi-products-to-cart', {carts: this.$storage.get('products_in_cart')})
                .then(({data}) => {
                    if (data.status) {
                        this.$storage.delete('products_in_cart');
                    }
                });
        }
    },
});
