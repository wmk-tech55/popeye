<!-- Modal -->

@php
$media = $product->allMedia();
@endphp

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
	  <div class="modal-content">
		  
		  <div class="modal-body">
			  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
				  <span aria-hidden="true">&times;</span>
			  </button>
  
			  <div class="products-images">
				
				@foreach ($media as $image)
				  <div class="image-item">
					  <img  src="{{ $image->getUrl() }}"  alt="{{$product->name_by_lang}}">
				  </div>
				  @endforeach
			  </div>
  
		  </div>
  
	  </div>
	</div>
  </div>
  