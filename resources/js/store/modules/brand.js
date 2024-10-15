import axios from "axios";
import { STORE_ALL_BRAND } from "../mutation-types";

// States
const state = {
    brands: [],
};

// Getters
const getters = {};

// Mutations
const mutations = {
    storeAllBrands (state, payload) {
        state.brands = payload;
    }
};

// Actions
const actions = {
    fetchAllBrands({ commit },payload) {
        axios.get(`/brands?lang=${payload.lang}`)
            .then(({data}) => {
                commit(STORE_ALL_BRAND, data.payload);
            });
    }
};

export default {
    state,
    getters,
    mutations,
    actions
}