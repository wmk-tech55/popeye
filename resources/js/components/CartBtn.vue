<template>
    <button
        v-if="! productDetailsStyle"
        class="cart"
        data-toggle="tooltip"
        data-placement="top"
        :title="btnTitle"
        :disabled="isDisabled"
        :class="{'disable-btn': isDisabled}"
        @click.prevent="addToCart"
    >
        <i class="fas fa-shopping-cart"></i>
    </button>

    <a v-else href="#" 
        class="btn btn-default btn-add-cart"
        :title="btnTitle"
        :disabled="isDisabled"
        :class="{'disable-btn': isDisabled}"
        @click.prevent="addToCart">
        <i class="fas fa-shopping-cart"></i>

        {{ btnTitle }}
    </a>
</template>

<script>
import { PUSH_TO_CART } from '../store/mutation-types';
export default {
    props: {
        product: {
            type: Object,
            required: true,
        },
        quantity: {
            type: Number,
            default: 1,
        },
        productDetailsStyle: {
            type: Boolean,
            default: false
        },
          allawRedirect: {
            type: Boolean,
            default: false
        }
    },
    data() {
        return {
            productItem: this.product
        };
    },
    computed: {
        isDisabled() {
            return (this.productItem.quantity <= 0 || this.productItem.is_in_cart);
        },
        btnTitle() {
            let btnTitle = this.$trans('add_to_cart');

            if(this.productItem.is_in_cart) {
                btnTitle = this.$trans('already_in_cart')
            }
            
            if(this.productItem.quantity <= 0) {
                btnTitle = this.$trans('no_quantity_available')
            }

            return btnTitle;
        },
    },
    mounted() {
        if (this.$isGuest) {
            this.$store.dispatch('check_if_product_in_local_cart', this.productItem.id).then(res => this.productItem.is_in_cart = res);
        }
    },
    methods: {
        addToCart() {
            if(this.$isAuth) {
                this.addProductToDBCart();
                
                if(this.allawRedirect){
             setTimeout(() => window.location = '/cart', 1000);   
            }
                return;
            }

            this.addProductToStorageCart();
            this.$toaster.success(this.$trans('product_added_to_cart'));
            if(this.allawRedirect){
             setTimeout(() => window.location = '/cart', 1000);   
            }
            
        },
        addProductToStorageCart() {
            this.productItem.is_in_cart = true;
            this.$store.commit(PUSH_TO_CART, {
                quantity: this.quantity,
                price: this.productItem.price_after_discount,
                total_price: this.productItem.price_after_discount,
                product: this.productItem,
            });

            this.$storage.set('products_in_cart', this.$store.state.cart.cartProducts);
        },
        addProductToDBCart() {
            this.productItem.is_in_cart = true;

            axios.post(`/products/${this.productItem.id}/cart`)
                .then(({ data }) => {
                    this.$store.dispatch('fetchAllCartProducts', {lang: window.getLocal});
                    this.$toaster.success(data.messages);
                    this.productItem = data.payload.product;
                })
                .catch(({response}) => {
                    this.productItem.is_in_cart = false;
                    this.$toaster.error(response.data.messages);
                });
        },
    }
};
</script>

<style scoped>
    .disable-btn {
        background-color: transparent !important;
        border: 1px solid #000 !important;
        color: #000 !important;
        cursor: not-allowed !important;
    }
</style>
