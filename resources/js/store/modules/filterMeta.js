import queryString from 'query-string';

// States
const state = {
    selectedCategories: [],
    selectedBrands: [],
    selectedPrice: [],
    selectedPriceRange: [],
    selectedSearch: [],
};

// Getters
const getters = {
    generateFilterKeys: (state, getters) => {
        return queryString.stringify(
            {
                categories: state.selectedCategories,
                brands: state.selectedBrands,
                price: state.selectedPrice,
                price_range: state.selectedPriceRange,
                search_with_key: state.selectedSearch,
            }, 
            {arrayFormat: 'bracket'}
        );
    },
    hasFilters: (state, getters) => {
        return (getters.categoryHadSelected || getters.brandHadSelected || getters.priceHadSelected || getters.priceRangeHadSelected ||  getters.searchHadSelected);
    },
    categoryHadSelected: (state, getters) => {
        return state.selectedCategories.length > 0;
    },
    brandHadSelected: (state, getters) => {
        return state.selectedBrands.length > 0;
    },
    priceHadSelected: (state, getters) => {
        return state.selectedPrice.length > 0;
    },
    priceRangeHadSelected: (state, getters) => {
        return state.selectedPriceRange.length > 0;
    },
    searchHadSelected: (state, getters) => {
        return state.selectedSearch.length > 0;
    },
    
};

// Mutations
const mutations = {
    updateSelectedCategories (state, payload) {
        state.selectedCategories = payload;
    },
    
    updateSelectedBrands (state, payload) {
        state.selectedBrands = payload;
    },

    updateSelectedPrice (state, payload) {
        state.selectedPrice = payload;
    },
 
    updateSelectedPriceRange (state, payload) {
        state.selectedPriceRange = payload;
    },

      
    updateSelectedSearch (state, payload) {
        state.selectedSearch = payload;
    },
};

// Actions
const actions = {};

export default {
    state,
    getters,
    mutations,
    actions
}