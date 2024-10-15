<template>
	<div class="row">
		<!-- Filters -->
		<products-filter></products-filter>

		<!-- Products -->
		<products-list></products-list>
	</div>
</template>

<script>
import ProductsFilter from '../components/ProductsFilter';
import ProductsList from '../components/ProductsList';

 
export default {
	components: {
		ProductsFilter,
		ProductsList,
	},
	data() {
		return {};
	},

	
};
</script>