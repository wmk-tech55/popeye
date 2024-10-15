<template>
    <li class="nav-item dropdown">
        <a
            class="nav-link dropdown-toggle"
            href="#"
            id="navbarDropdownCart"
            role="button"
            data-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="false"
        >
            <i class="fas fa-shopping-bag"></i>
            <span v-if="haveAnyProducts" v-text="cartProducts.length"></span>
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownCart">

            <div v-if="! haveAnyProducts" class="alert alert-dark text-center m-1" role="alert" v-text="$trans('no_items_in_cart')"></div>

            <div class="all-items" v-else>
                
				<ul>
                    <li v-for="cartProduct in cartProducts" :key="cartProduct.id">
                        
						<span class="product-image">
                            <img :src="cartProduct.product.main_media_url" :alt="cartProduct.product.name_by_lang" />
                        </span>

                        <span class="product-name"
                        v-text="  cartProduct.product.name_by_lang.substring( 0, 15 ) + '..' " >
                        </span>
                        <span class="quntity" v-text="cartProduct.quantity"></span>
                        <span class="unit-price" v-text="cartProduct.product.price_after_discount+' '+$trans('currency')"></span>
                        <span class="total-price" v-text="cartProduct.total_price+' '+$trans('currency')"> </span>
                        
						<!-- <cart-delete-btn :cart="cartProduct.id"></cart-delete-btn> -->
                    </li>
                    
                </ul>

                <div class="btn-wrapper text-center">
                    <a href="/cart" class="btn btn-outline-custom-invert" v-text="$trans('view_cart')"></a>
                    <a v-if="$isAuth" href="/checkout" class="btn btn-custom-dark" v-text="$trans('view_checkout')"></a>
                </div>
            </div>
        </div>
    </li>
</template>

<script>
import { Add_TO_CART } from '../store/mutation-types';
import CartDeleteBtn from './CartDeleteBtn.vue';
export default {
  components: { CartDeleteBtn },
    data() {
        return {};
    },
    computed: {
        cartProducts() {
            return this.$store.state.cart.cartProducts;
        },
        haveAnyProducts() {
            return this.$store.getters.haveAnyProducts;
        },
    },
    mounted() {
        if (this.$isAuth) {
            this.$store.dispatch('fetchAllCartProducts', {lang: window.getLocal});
        } else {
            let cartProducts = this.$storage.get('products_in_cart');
            cartProducts = (cartProducts === null) ? [] : cartProducts;

            this.$store.commit(Add_TO_CART, cartProducts);
        }
	},
    methods: {}
};
</script>
