<section id="todayDeals" class="toddeals-products">
	<div class="heading">
		<h6>
			@lang('site.best_seller')
		</h6>
	</div>
	<div class="products-list">

		@foreach ($bestSellingProductsByCategory as $product)
	 
		<div class="item">
			<a href="{{$product->viewPath()}}">
			<img src="{{ $product->mainMediaUrl() }} " alt="{{shortCleanString($product->name_by_lang,'40') }}">
		</a>
			@if (!!$product->discount)
			<div class="sale-badge"> @lang('site.sale') </div>
			@endif

			<p> <a href="{{$product->viewPath()}}">{{shortCleanString($product->name_by_lang,'40') }} </a> </p>
			<h6>
				{{$product->price_after_discount}} {{currency()}}

				@if (!!$product->discount)
				<small> {{$product->price}} {{currency()}}</small>
				@endif

			</h6>
			<div class="rate">
				@php
			  $averageRate = $product->average_rate;

			  @endphp
			  <ul>
				  <li class="{{ $averageRate >= 1 ? 'active' : '' }}">
					  <i class="icofont-star"></i>
				  </li>
				  <li class="{{ $averageRate >= 2 ? 'active' : '' }}">
					  <i class="icofont-star"></i>
				  </li>
				  <li class="{{ $averageRate >= 3 ? 'active' : '' }}">
					  <i class="icofont-star"></i>
				  </li>
				  <li class="{{ $averageRate >= 4 ? 'active' : '' }}">
					  <i class="icofont-star"></i>
				  </li>
				  <li class="{{ $averageRate >= 5 ? 'active' : '' }}">
					  <i class="icofont-star"></i>
				  </li>
			  </ul>
		  </div>

			<div class="wrapper-btns">
			     	<livewire:add-to-cart-button key="{{time()}}" :product="$product">
					<livewire:favorite key="{{time()}}" :product="$product">
			</div>


		</div>
		@endforeach

	</div>
</section>