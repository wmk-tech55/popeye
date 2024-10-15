
<hr>
<div class="col-xl-12 col-md-12 mt-4 mb-3">
    <h1 class="h4 font-weight-bold mb-0 text-gray-800">@lang('main.files')</h1>
</div>
<div class="col-md-12 col-sm-12">
    <div class="h6">
        <span class="font-weight-bold">@lang('main.vehicle_license'): </span>



<span class="text-muted">
            @if (pathinfo($driver->allUserMedia['vehicle_license'], PATHINFO_EXTENSION) == 'pdf')
                <embed src="{{ $driver->allUserMedia['vehicle_license'] }}"  >
            @else
                <img class="fixed-image-size img-thumbnail" src="{{ $driver->allUserMedia['vehicle_license'] }}"
                    alt="{{ $driver->full_name }}" />
            @endif

        </span>

    </div>
    <hr>
</div>

<!-- Page Heading -->
<div class="col-xl-12 col-md-12 mt-4 mb-3">
    <h1 class="h4 font-weight-bold mb-0 text-gray-800">@lang('main.vehicle_photos')</h1>
</div>
<div class="col-xl-12 col-md-12 mt-4 mb-3">
    <div class="grid d-flex">


        @php
            $media = $driver->allMedia();
        @endphp

        <div class="grid-col grid-col--1"></div>
        <div class="grid-col grid-col--2 d-sm-none d-md-block"></div>
        <div class="grid-col grid-col--3 d-sm-none d-lg-block"></div>
        <div class="grid-col grid-col--4"></div>

        @foreach ($media as $image)
            <div class="card grid-item image-container m-1">
                <img class="card-img portfolio-image" width="200" height="200" src="{{ $image->getUrl() }}">

                <button type="button" class="btn btn-danger btn-sm delete-image" data-id="{{ $image->id }}">
                    <i class="fas fa-trash"></i>
                </button>

            </div>
        @endforeach

    </div>
</div>
