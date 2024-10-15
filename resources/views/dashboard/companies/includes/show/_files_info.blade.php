
<div class="col-md-12 col-sm-12">
    <div class="h6">
        <span class="font-weight-bold">@lang("main.logo"): </span>

        <span class="text-muted">
            <img loading="lazy" class="fixed-image-size img-thumbnail" src="{{ $company->mainMediaUrl('logo') }}">
        </span>

    </div>
    <hr>
</div> 

<div class="col-md-12 col-sm-12">
    <div class="h6">
        <span class="font-weight-bold">@lang("main.commercial_license"): </span>

        <span class="text-muted">
            @if (!empty($company->allUserMedia['commercial_license']))
                @if (pathinfo($company->allUserMedia['commercial_license'], PATHINFO_EXTENSION) == 'pdf')
                    <embed src="{{ $company->allUserMedia['commercial_license'] }}" width="300" height="300">
                @else
                    <img class="fixed-image-size img-thumbnail" src="{{ $company->allUserMedia['commercial_license'] }}" alt="{{ $company->full_name }}" />
                @endif
            @else
                <span class="text-danger">@lang('main.no_commercial_license')</span>
            @endif
        </span>

    </div>
    <hr>
</div>

@foreach ($media as $media_name => $media_link)

     @if (in_array($media_name, ['logo', 'commercial_license']) && !! $media_link)
        <div class="col-md-6 col-sm-12">
            <div class="h6">
                <span class="font-weight-bold">@lang("main.{$media_name}"): </span>

                <span class="text-muted">
                    <a href="{{ $media_link }}" class="btn btn-primary btn-sm" target="_blank" rel="noopener noreferrer">
                        <i class="fas fa-link"></i>
                        @lang('main.visit_url')
                    </a>
                </span>

            </div>
            <hr>
        </div>
    
    @endif
@endforeach
    
