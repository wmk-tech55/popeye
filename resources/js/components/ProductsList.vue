<template>
    <div class="col-lg-9 col-md-12 col-sm-12">
        <div class="category-wrapper">
            <div class="heading">
                <h1 v-if="resultsFor">{{ $trans("results_for") }} : {{ resultsFor }}</h1>
            </div>
            <hr v-if="resultsFor"/>
            <div class="row">
                <div
                    class="col-lg-3 col-md-6 col-sm-6"
                    v-for="product in products"
                    :key="product.id"
                >
                    <div class="product-layout">
                        <div class="product-thumb">
                            <div class="image">
                                <a :href="`products/${product.id}`">
                                    <img :src="product.main_media_url" :alt="product.name_by_lang"/>
                                </a>
                                <div
                                    v-if="!!product.discount"
                                    class="sale"  >
                                <p v-text="$trans('sale')"></p>
                                </div>

                                <div class="sale" v-if="product.quantity <= 0">
                                    <p v-text="$trans('is_out_of_stock')"></p>
                                </div>
                            </div>
                            <div class="caption">
                                <h5>
                                    <a
                                        :href="`products/${product.id}`"
                                        v-text="
                                            product.name_by_lang.substring(
                                                0,
                                                20
                                            ) + '..'
                                        "
                                    ></a>
                                </h5>
                                <h3>
                                     {{ product.price_after_discount }} {{$trans('currency')}}
                                    <small
                                        class="old-price"
                                        v-if="!!product.discount"
                                        > {{ product.price }} {{$trans('currency')}}</small
                                    >
                                </h3>
                                <div class="rate">
                                    <ul>
                                        <li :class="{'active': product.average_rate >= 1 }">
                                            <i class="fas fa-star"></i>
                                        </li>
                                        <li :class="{'active': product.average_rate >= 2 }">
                                            <i class="fas fa-star"></i>
                                        </li>
                                        <li :class="{'active': product.average_rate >= 3 }">
                                            <i class="fas fa-star"></i>
                                        </li>
                                        <li :class="{'active': product.average_rate >= 4 }">
                                            <i class="fas fa-star"></i>
                                        </li>
                                        <li :class="{'active': product.average_rate >= 5}">
                                            <i class="fas fa-star"></i>
                                        </li>
                                    </ul>
                                </div>
                               <favorite-btn :product="product"></favorite-btn>
								
								<!-- Cart Button -->
                                <cart-btn :product="product"></cart-btn>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <hr />
        <div class="product-pager">
               <pagination  :data="productsResponse" @pagination-change-page="refreshList" ></pagination>
           </div>
        </div>
    </div>
</template>

<script>
import CartBtn from './CartBtn.vue';
export default {
  components: { CartBtn },
    data() {
        return {
            serachResultFor: ""
        };
    },
    computed: {
 
        products() {
            return this.$store.state.product.products;
        },
        productsResponse() {
            return this.$store.state.product.response;
        },
        hasFilters() {
            return this.$store.getters.hasFilters;
        },
        resultsFor() {
            let uri = window.location.search.substring(1);

            let params = new URLSearchParams(uri);

            if (params.get("search")) {
                this.serachResultFor = params.get("search");
            }

            if (params.get("n")) {
                this.serachResultFor = params.get("n");
            }

            return this.serachResultFor;
        }
    },
    mounted() {
        // Get All Products
        this.refreshList();
    },
    methods: {
            
        refreshList(page = 1) {
            if (this.hasFilters) {
                // Filter Products
                let categories = this.$store.getters.generateFilterKeys;

                this.$store.dispatch("filterProducts", {
                    filters: this.$store.getters.generateFilterKeys,
                    page,
                    lang: window.getLocal
                });

                return;
            }

            this.$store.dispatch("fetchAllProducts", {
                page,
                lang: window.getLocal
            });
        }
    }
};
</script>
