<template>
 
    <span class="delete">
        <button
         class="btn btn-default btn-delete btn-danger"
             @click.prevent="deleteCart"
         >
             <i class="far fa-trash-alt"></i>
           </button>
               </span>

</template>

<script>
import { DELETE_CART } from '../store/mutation-types';
 export default {
    props: ["cart", 'cart_index'],
    data() {
        return {
            cartItem: this.cart
        };
    },
    methods: {
        deleteCart() {
            
            if (this.$isGuest) {
                this.$store.commit(DELETE_CART, {cartIndex: this.cart_index});
                this.$storage.set('products_in_cart', this.$store.state.cart.cartProducts);
                return;
            }

            axios.delete(`/cart/${this.cartItem}/destroy`)
                .then(({ data }) => {
                    this.$toaster.success(data.messages);
                     this.$store.dispatch('fetchAllCartProducts', {lang: window.getLocal});
                })
                .catch(({response}) => {
                     this.$toaster.error(response.data.messages);
                });
        }
    }
};
</script>
 