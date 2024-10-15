import axios from "axios";
import { STORE_ALL_CATEGORIES } from "../mutation-types";

// States
const state = {
    categories: [],
};

// Getters
const getters = {};

// Mutations
const mutations = {
    storeAllCategories (state, payload) {
        state.categories = payload;
    }
};

// Actions
const actions = {
    fetchAllCategories({ commit }, payload) {
        axios.get(`/categories?lang=${payload.lang}`)
            .then(({data}) => {
                commit(STORE_ALL_CATEGORIES, data.payload);
            });
    }
};

export default {
    state,
    getters,
    mutations,
    actions
}