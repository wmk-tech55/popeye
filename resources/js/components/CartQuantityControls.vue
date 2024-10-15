<template>
    <form class="d-flex justify-content-around align-content-center" >
        <div class="form-group">
            <button
                class="btn btn-outline-custom-invert btn-miun"
                @click.prevent="increase()"
                name="increase_quantity"
                type="button"
                :disabled="isCartDisabled"
                :class="{'disable-btn': isCartDisabled}">

                <i class="fas fa-plus"></i>

                <span
                    v-if="isCartDisabled"
                    class="spinner-border spinner-border-sm"
                    role="status"
                    aria-hidden="true"
                ></span>
                <span class="sr-only"> </span>
            </button>

            <input class="form-control" type="number" min="1" name="quantity" :value="cartProduct.quantity" disabled /> 

            <button
                class="btn btn-outline-custom-invert btn-plus"
                @click.prevent="decrease()"
                type="button"
                name="decrease_quantity"
                :disabled="isCartDisabled"
                :class="{'disable-btn': isCartDisabled}">

                <i class="fas fa-minus"></i>

                <span
                    v-if="isCartDisabled"
                    class="spinner-border spinner-border-sm"
                    role="status"
                    aria-hidden="true"
                ></span>
                <span class="sr-only"> </span>
            </button>
        </div>
    </form>
</template>

<script>
export default {
    props: ['isCartDisabled', 'cartProduct', 'index'],
    methods: {
        increase() {
            this.$emit('increase', this.cartProduct.id, this.index, this.cartProduct.product.id)
        },
        decrease() {
            this.$emit('decrease', this.cartProduct.id, this.index, this.cartProduct.product.id)
        },
    }
}
</script>
