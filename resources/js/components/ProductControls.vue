<template>    
    <div class="btn-wrapper">
        <cart-btn :product="productItem" :productDetailsStyle="true" :allawRedirect="true" ></cart-btn>

        <a v-if="haveAnyProducts" href="/cart" class="btn btn-default btn-add-cart">
            <i class="fas fa-shopping-bag"></i>
            {{ $trans('checkout') }}
        </a>

        <favorite-btn :product="productItem" :productDetailsStyle="true"></favorite-btn>
    </div>
</template>

<script>
export default {
    props: ['product'],
    data() {
        return {
            productItem: this.product,
        };
    },
    computed: {
        haveAnyProducts() {
            return this.$store.getters.haveAnyProducts;
        },
    },
    methods: {
        updateProductCartStatus(product) {
            this.productItem = product;
        },
        decrease_quantity(cartId, index, productId) {
            this.update_quantity = "decrease_quantity";
            this.isCartDisabled = true;
            
            this.$store.commit(DECREASE_CART_QUANTITY, { cartIndex: index });
            
            if (this.$isGuest) {
                this.$storage.set('products_in_cart', this.cartProducts);
            }

            this.isCartDisabled = false;

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
                    this.$store.commit(INCREASE_CART_QUANTITY, { cartIndex: index, productQuantity: product.quantity });

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
}
</script>
