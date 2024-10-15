<template>
    <div class="col-md-12 col-sm-12">
        <h1>{{ $trans("cart_items") }} ({{ cartProducts.length }})</h1>
        <div class="tabel-responsive">
            <table class="table table-hover table-bordered text-center">
                <thead class="thead-dark">
                    <tr>
                        <th v-text="$trans('delete')"></th>
                        <th v-text="$trans('product_image')"></th>
                        <th v-text="$trans('product_name')"></th>
                        <th v-text="$trans('quantity')"></th>
                        <th v-text="$trans('price')" class="text-right"></th>
                        <th v-text="$trans('total')" class="text-right"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(cartProduct, index) in cartProducts" :key="cartProduct.id">
                        <td class="text-center">
                            <cart-delete-btn :cart_index="index" :cart="cartProduct.id"></cart-delete-btn>
                        </td>
                        <td class="text-center">
                            <a :href="`products/` + cartProduct.product.id" target="_new">
                                <img :src="cartProduct.product.main_media_url" :alt="cartProduct.product.name_by_lang" />
                            </a>
                        </td>

                        <td class="text-center">
                            <a :href="`products/` + cartProduct.product.id" target="_new" v-text="cartProduct.product.name_by_lang.substring(0, 15) + '..'"></a>
                        </td>
                        
                        <td v-if="! isCartDisabled" class="text-center">
                            <cart-quantity-controls
                                :cartProduct="cartProduct"
                                :index="index"
                                :isCartDisabled="isCartDisabled"
                                @increase="increase_quantity"
                                @decrease="decrease_quantity"
                            ></cart-quantity-controls>
                        </td>

                        <td v-if="isCartDisabled" class="text-center">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" ></span>
                        </td>

                        <td class="text-right">
                            {{ cartProduct.price }}
                            {{ $trans('currency') }}
                        </td>

                        <td class="text-right">
                            {{ cartProduct.total_price }}
                            {{ $trans("currency") }}
                        </td>
                    </tr>

                    <tr v-if="!haveAnyProducts">
                        <td colspan="6" class="text-center">
                            {{ $trans("There_is_no_products_in_cart") }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <table class="table table-striped table-bordered total" v-if="haveAnyProducts" >
                <tbody>
                    <tr>
                        <td v-text="$trans('total')"></td>
                        <td v-text="totalCartPrice + ' ' + $trans('currency')"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
import { DECREASE_CART_QUANTITY, INCREASE_CART_QUANTITY } from "../store/mutation-types";
import CartQuantityControls from './CartQuantityControls';
export default {
    components: { CartQuantityControls, },
    data() {
        return {
            update_quantity: "",
            isCartDisabled: false
        };
    },
    computed: {
        cartProducts() {
            return this.$store.state.cart.cartProducts;
        },
        haveAnyProducts() {
            return this.$store.getters.haveAnyProducts;
        },
        totalCartPrice() {
            return this.$store.getters.totalCartPrice;
        }
    },
    mounted() {
        if (this.$isAuth) {
            this.$store.dispatch('fetchAllCartProducts', {lang: window.getLocal});
        }
    },
    methods: {
        decrease_quantity(cartId, index, productId) {
            this.update_quantity = "decrease_quantity";
            this.isCartDisabled = true;

            console.log("Debug:: ", this.isCartDisabled);
       
            this.$store.dispatch("getProductById", {id: productId, lang: window.getLocal})
                .then(product => {
                    this.isCartDisabled = false;
                    this.$store.commit(DECREASE_CART_QUANTITY, { cartIndex: index , has_special_price:product.has_special_price , specialPrice: product.special_price , product_original_price: product.price_after_discount});
                    
                    if (this.$isGuest) {
                        this.$storage.set('products_in_cart', this.cartProducts);
                    }
                });

            if (this.$isAuth) {
                this.updateCartQuanitty(cartId);
                return;
            }
        },
        
        increase_quantity(cartId, index, productId) {
            this.update_quantity = "increase_quantity";
            this.isCartDisabled = true;

            this.$store.dispatch("getProductById", {id: productId, lang: window.getLocal})
                .then(product => {
                    this.isCartDisabled = false;
                    this.$store.commit(INCREASE_CART_QUANTITY, { cartIndex: index, productQuantity: product.quantity , has_special_price:product.has_special_price , specialPrice: product.special_price ,product_original_price: product.price_after_discount});

                    if (this.$isGuest) {
                        this.$storage.set('products_in_cart', this.cartProducts);
                    }
                });

            if (this.$isAuth) {
                this.updateCartQuanitty(cartId);
                return;
            }
        },

        updateCartQuanitty(cartId) {
            return axios.patch(`/cart/${cartId}/update?update_quantity=${this.update_quantity}`)
                .then(({ data }) => {
                    this.$store.dispatch("fetchAllCartProducts", {
                        lang: window.getLocal
                    });
                    this.isCartDisabled = false;
                })
                .catch(({ response }) => {
                    this.isCartDisabled = false;
                });
        },
    }
};
</script>
