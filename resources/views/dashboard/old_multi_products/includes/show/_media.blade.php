
  <!-- Page Heading -->
  <div class="col-xl-12 col-md-12 mt-4 mb-3">
    <h1 class="h4 font-weight-bold mb-0 text-gray-800">@lang('main.images')</h1>
</div>

<div class="grid d-flex">


    @php
    $media = $product->allMedia();
    @endphp

    <div class="grid-col grid-col--1"></div>
    <div class="grid-col grid-col--2 d-sm-none d-md-block"></div>
    <div class="grid-col grid-col--3 d-sm-none d-lg-block"></div>
    <div class="grid-col grid-col--4"></div>

    @foreach ($media as $image)
    <div class="card grid-item image-container">
        <img class="card-img portfolio-image" src="{{ $image->getUrl() }}">

        <button type="button" class="btn btn-danger btn-sm delete-image" data-id="{{ $image->id }}">
            <i class="fas fa-trash"></i>
        </button>

    </div>
    @endforeach

</div>

@if ($product->mainMediaUrl('video'))
    
<hr>

  <!-- Page Heading -->
  <div class="col-xl-12 col-md-12 mt-4 mb-3">
    <h1 class="h4 font-weight-bold mb-0 text-gray-800">@lang('main.video')</h1>
</div>

<div class="embed-responsive embed-responsive-16by9">
    <iframe class="embed-responsive-item"  style="width:700px !important" src="{{$product->mainMediaUrl('video')}}" allowfullscreen></iframe>
</div>
@endif
