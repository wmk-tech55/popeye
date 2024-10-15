import { Add_TO_CART } from "../mutation-types";

// States
const state = {
    cartProducts: []
};

// Getters
const getters = {
    haveAnyProducts: (state, getters) => {
        return state.cartProducts.length > 0;
    },
    totalCartPrice: (state, getters) => {
        return state.cartProducts.reduce((a, b) =>  parseFloat(a)  + parseFloat(b.total_price), 0) ;
    }
};

// Mutations
const mutations = {
    addToCart (state, payload) {
        state.cartProducts = payload;
    },
    pushToCart (state, payload) {
        state.cartProducts.push(payload);
    },
    increaseCartQuantity (state, data) {
        const cartProduct = state.cartProducts[data.cartIndex];
        
        let quantity     = cartProduct.quantity + 1;
        let cartPrice    = cartProduct.price ;

        if (quantity > data.productQuantity) return;
 
        if( data.has_special_price == 'yes'){
          
        var special_price_items =  data.specialPrice.filter(function(data) {
            return quantity >= data.special_quantity ;
        });

        if(special_price_items.length > 0){
               var last_special_price_item = special_price_items[special_price_items.length - 1];

                cartPrice = last_special_price_item.special_price ; 

             }else{
                cartPrice = data.product_original_price ; 
              }
        }
 

         cartProduct.quantity = quantity;
       
         cartProduct.price    = cartPrice;
        
         cartProduct.total_price = cartProduct.price * cartProduct.quantity;
         
         state.cartProducts.splice(data.cartIndex, 1, cartProduct);
    },
    decreaseCartQuantity (state, data) {
       
        const cartProduct = state.cartProducts[data.cartIndex];

        if (cartProduct.quantity == 1) return;

        let quantity     = cartProduct.quantity -  1;
      
        var cartPrice    = cartProduct.price ;
 
        if( data.has_special_price == 'yes'){
          
            var special_price_items =  data.specialPrice.filter(function(data) {
                return quantity >= data.special_quantity ;
            });
 
            if(special_price_items.length > 0){
                   var last_special_price_item = special_price_items[0];
    
                    cartPrice = last_special_price_item.special_price ; 

                  }else{
                    cartPrice = data.product_original_price ; 
                  }
            }

        cartProduct.quantity = quantity; 
       
        cartProduct.price    = cartPrice;
        
        cartProduct.total_price = cartProduct.price * cartProduct.quantity;
 
        state.cartProducts.splice(data.cartIndex, 1, cartProduct);
    },
    deleteCart (state, data) {
        state.cartProducts.splice(data.cartIndex, 1);
    },
};

// Actions
const actions = {
    fetchAllCartProducts({ commit }, payload) {
        axios.get(`/cart?lang=${payload.lang}`).then(({data}) => commit(Add_TO_CART, data.payload));
    },
    check_if_product_in_local_cart({ state }, productId) {
        let index = state.cartProducts.findIndex(cart => cart.product.id === productId);
        
        return (index > -1);
    },
    getCartByProductId({ state }, productId) {
        let index = state.cartProducts.findIndex(cart => cart.product.id === productId);
        
        return {
            product: state.cartProducts[index],
            index,
        };
    },
};

export default {
    state,
    getters,
    mutations,
    actions
}