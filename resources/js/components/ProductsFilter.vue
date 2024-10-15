<template>
    <div class="col-lg-3 col-md-12 col-sm-12">
        <div class="form">
            <form action="" @submit.prevent>
                <div class="accordion" id="accordionFilter">
                    <!-- price range  -->
                    <div class="card block text-direction-auto">
                        <div class="card-header" id="heading1">
                            <h6 class="mb-0">
                                <button
                                    class="btn btn-link btn-block text-direction-auto collapsed"
                                    type="button"
                                    data-toggle="collapse"
                                    data-target="#priceRange-collapse1"
                                    aria-expanded="false"
                                    aria-controls="collapse3"
                                >
                                    {{ $trans("price_range") }}
                                </button>
                            </h6>
                        </div>
                        <div
                            id="priceRange-collapse1"
                            class="collapse show"
                            aria-labelledby="heading1"
                            data-parent="#accordionFilter"
                            dir="ltr"
                        >
                            <div class="card-body text-left">
                                <div class="form-group text-left">
                                    <input
                                        ref="price_range"
                                        type="range"
                                        class="js-range-slider text-left"
                                        data-min="0"
                                        data-max="20000"
                                        data-from="0"
                                        data-to="100"
                                        data-grid="true"
                                        name="priceRange"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--------- sort by block ---------->
                    <div class="card block text-direction-auto">
                        <div class="card-header" id="heading2">
                            <h6 class="mb-0">
                                <button
                                    class="btn btn-link btn-block text-direction-auto collapsed"
                                    type="button"
                                    data-toggle="collapse"
                                    data-target="#sort_by-collapse2"
                                    aria-expanded="false"
                                    aria-controls="collapse2"
                                >
                                    {{ $trans("sort_by") }}
                                </button>
                            </h6>
                        </div>
                        <div
                            id="sort_by-collapse2"
                            class="collapse"
                            aria-labelledby="heading2"
                            data-parent="#accordionFilter"
                        >
                            <div class="card-body">
                                <div class="form-group">
                                    <div class="pretty p-icon p-round p-smooth">
                                        <input
                                            type="radio"
                                            v-model="selectedPrice"
                                            :value="`price_low`"
                                            @change="filter"
                                        />
                                        <div class="state p-success">
                                            <i class="icon mdi mdi-check"></i>
                                            <label
                                                v-text="
                                                    $trans('price_low_to_hight')
                                                "
                                            >
                                            </label>
                                        </div>
                                    </div>
                                    <div class="pretty p-icon p-round p-smooth">
                                        <input
                                            type="radio"
                                            v-model="selectedPrice"
                                            :value="`price_high`"
                                            @change="filter"
                                        />
                                        <div class="state p-success">
                                            <i class="icon mdi mdi-check"></i>
                                            <label
                                                v-text="
                                                    $trans('price_hight_to_low')
                                                "
                                            ></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--------- Categories block ---------->

                    <div class="card block text-direction-auto">
                        <div class="card-header" id="heading3">
                            <h6 class="mb-0">
                                <button
                                    class="btn btn-link btn-block text-direction-auto collapsed"
                                    type="button"
                                    data-toggle="collapse"
                                    data-target="#categories-collapse2"
                                    aria-expanded="false"
                                    aria-controls="collapse2"
                                >
                                    {{ $trans("categories") }}
                                </button>
                            </h6>
                        </div>
                        <div
                            id="categories-collapse2"
                            class="collapse"
                            aria-labelledby="heading3"
                            data-parent="#accordionFilter"
                        >
                            <div class="card-body">
                                <div class="form-group">
                                    <div
                                        v-for="category in categories"
                                        :key="category.id"
                                        class="pretty p-icon p-round p-smooth"
                                    >
                                        <input
                                            type="checkbox"
                                            name="categories[]"
                                            v-model="selectedCategories"
                                            :value="category.id"
                                            @change="filter"
                                        />
                                        <div class="state p-success">
                                            <i class="icon mdi mdi-check"></i>
                                            <label
                                                v-text="category.name_by_lang"
                                            ></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!----------Brands Block--------->

                    <div class="card block text-direction-auto">
                        <div class="card-header" id="heading4">
                            <h6 class="mb-0">
                                <button
                                    class="btn btn-link btn-block text-direction-auto collapsed"
                                    type="button"
                                    data-toggle="collapse"
                                    data-target="#brands-collapse2"
                                    aria-expanded="false"
                                    aria-controls="collapse2"
                                >
                                    {{ $trans("brand") }}
                                </button>
                            </h6>
                        </div>
                        <div
                            id="brands-collapse2"
                            class="collapse"
                            aria-labelledby="heading4"
                            data-parent="#accordionFilter"
                        >
                            <div class="card-body">
                                <div class="form-group">
                                    <div
                                        v-for="brand in brands"
                                        :key="brand.id"
                                        class="pretty p-icon p-round p-smooth"
                                    >
                                        <input
                                            type="checkbox"
                                            name="brands[]"
                                            v-model="selectedBrands"
                                            :value="brand.id"
                                            @change="filter"
                                        />
                                        <div class="state p-success">
                                            <i class="icon mdi mdi-check"></i>
                                            <label
                                                v-text="brand.name_by_lang"
                                            ></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- # End -->
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import {
    FILTER_AND_UPDATE_SELECTED_CATEGORIES,
    FILTER_AND_UPDATE_SELECTED_BRANDS,
    FILTER_AND_UPDATE_SELECTED_PRICE,
    FILTER_AND_UPDATE_SELECTED_PRICE_RANGE,
    FILTER_AND_UPDATE_SELECTED_SEARCH_KEY,
    CHANGE_APP_LANG
} from "../store/mutation-types";

export default {
    data() {
        return {
            selectedCategories: [],
            selectedBrands: [],
            selectedPrice: "",
            selectedPriceRange: "",
            selectedSearch: ""
        };
    },
    computed: {
        categories() {
            return this.$store.state.category.categories;
        },
        brands() {
            return this.$store.state.brand.brands;
        },
        total: function() {
            return this.selectedPriceRange;
        }
    },
    mounted() {
        // Get All Categories
        this.$store.dispatch("fetchAllCategories", { lang: window.getLocal });

        // Get All Brands
        this.$store.dispatch("fetchAllBrands", { lang: window.getLocal });

        let uri = window.location.search.substring(1);

        let params = new URLSearchParams(uri);

        if (params.get("search")) {
            this.selectedSearch = params.get("search");
        }

        if (params.get("cat")) {
            this.selectedCategories = [params.get("cat")];
        }

        this.queryStringWithSearchKey();

        this.queryStringWithCategories();

        const self = this;

        $(this.$refs.price_range).ionRangeSlider({
            skin: "square", 
            onFinish(data) {
                self.selectedPriceRange = data.from;
                self.filter()
            },
        });
        
    },

    methods: {
        filter() {
            this.$store.commit(
                FILTER_AND_UPDATE_SELECTED_CATEGORIES,
                this.selectedCategories
            );
            this.$store.commit(
                FILTER_AND_UPDATE_SELECTED_BRANDS,
                this.selectedBrands
            );
            this.$store.commit(
                FILTER_AND_UPDATE_SELECTED_PRICE,
                this.selectedPrice
            );

            this.$store.commit(
                FILTER_AND_UPDATE_SELECTED_PRICE_RANGE,
                this.selectedPriceRange
            );

            this.$store.dispatch("filterProducts", {
                filters: this.$store.getters.generateFilterKeys,
                page: 1,
                lang: window.getLocal
            });
        },
        queryStringWithSearchKey() {
            this.$store.commit(
                FILTER_AND_UPDATE_SELECTED_SEARCH_KEY,
                this.selectedSearch
            );
            this.$store.dispatch("filterProducts", {
                filters: this.$store.getters.generateFilterKeys,
                page: 1,
                lang: window.getLocal
            });
        },

        queryStringWithCategories() {
            this.$store.commit(
                FILTER_AND_UPDATE_SELECTED_CATEGORIES,
                this.selectedCategories
            );
            this.$store.dispatch("filterProducts", {
                filters: this.$store.getters.generateFilterKeys,
                page: 1,
                lang: window.getLocal
            });
        }
    }
};
</script>
