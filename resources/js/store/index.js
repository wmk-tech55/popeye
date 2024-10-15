import Vue from 'vue';
import Vuex from 'vuex';
import brand from './modules/brand';
import cart from './modules/cart';
import category from './modules/category';
import filterMeta from './modules/filterMeta';
import lang from './modules/lang';
import product from './modules/product';

Vue.use(Vuex);

const store = new Vuex.Store({
    modules: {
        filterMeta,
        cart,
        product,
        category,
        brand,
        lang
    }
});

export default store;