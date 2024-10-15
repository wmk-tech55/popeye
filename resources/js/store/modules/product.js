import axios from "axios";
import { STORE_ALL_PRODUCTS } from "../mutation-types";

// States
const state = {
    products: [],
    response: {},
};

// Getters
const getters = {};

// Mutations
const mutations = {
    storeAllProducts (state, payload) {
        state.products = payload;
    },
};

// Actions
const actions = { 
    fetchAllProducts({ commit, state }, payload) {
        return axios.get(`/products/search?lang=${payload.lang}&page=${payload.page}`)
            .then(({data}) => {
                commit(STORE_ALL_PRODUCTS, data.payload);
                
                state.response = data;
                
                return data;
            });
    },
    getProductById({ commit, state }, payload) {
        return axios.get(`/products/${payload.id}?lang=${payload.lang}`).then(({data}) =>  data.payload);
    },
    filterProducts({ commit, state }, payload) {  
        return axios.get(`/products/search?lang=${payload.lang}&${payload.filters}&page=${payload.page}`)
            .then(({data}) => {
                commit(STORE_ALL_PRODUCTS, data.payload);

                state.response = data;
                
                return data;
            });
    },
};

export default {
    state,
    getters,
    mutations,
    actions
}