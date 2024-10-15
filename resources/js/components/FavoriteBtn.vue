<template>
    <button
        v-if="! productDetailsStyle"
        class="wish"
        data-toggle="tooltip"
        data-placement="top"
        :title="$trans('add_to_favorite')"
        @click.prevent="favorite"
        :checked="productItem.is_favorited">
        <i class="fas fa-heart"></i>
    </button>

    <a v-else href="#" 
        data-toggle="tooltip" 
        data-placement="top" 
        :title="$trans('add_to_favorite')"
        @click.prevent="favorite"
        :class="{'favorited': productItem.is_favorited}">
        <span><i class="fas fa-heart"></i></span>
    </a>
</template>

<script>
export default {
    props: {
        product: {
            type: Object,
            required: true,
        },
        productDetailsStyle: {
            type: Boolean,
            default: false
        }
    },
    data() {
        return {
            productItem: this.product
        };
    },
    mounted() {
        window.Events.$on("sync-product-favorited", this.syncFavorite);
    },
    methods: {
        favorite() {
            let methodUrl = this.productItem.is_favorited
                ? "mark-as-un-favorite"
                : "mark-as-favorite";
            let url = `/products/${this.productItem.id}/${methodUrl}`;

            if (this.productItem.is_favorited) {
                this.productItem.is_favorited = false;
                axios.delete(url).then(({ data }) => {
                    window.Events.$emit("sync-product-favorited", data.payload);
                    this.$toaster.success(data.messages);
                });
            } else {
                this.productItem.is_favorited = true;
                axios.post(url, {}).then(({ data }) => {
                    window.Events.$emit("sync-product-favorited", data.payload);
                    this.$toaster.success(data.messages);
                });
            }
        },
        syncFavorite(product) {
            if (this.productItem.id === product.id) {
                this.productItem = product;
            }
        }
    }
};
</script>

<style scoped>
    .favorited {
        color: var(--red) !important;
    }
</style>