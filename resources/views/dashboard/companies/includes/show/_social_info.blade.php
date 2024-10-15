{{-- Facebook --}}
<div class="col-md-2 col-sm-12">
    <div class="h6">
        <span class="font-weight-bold">@lang("main.facebook"): </span>
        @if (!! $company->facebook)
            <a href="{{ $company->facebook }}" class="btn btn-danger btn-sm" target="_blank" rel="noopener noreferrer">
                <i class="fas fa-link"></i>
                
            </a>
        @else
            <span class="text-muted">
                @lang('main.not_found')
            </span>
        @endif

    </div>
    <hr>
</div>

{{-- Twitter --}}
<div class="col-md-2 col-sm-12">
    <div class="h6">
        <span class="font-weight-bold">@lang("main.twitter"): </span>
        @if (!! $company->twitter)
            <a href="{{ $company->twitter }}" class="btn btn-danger btn-sm" target="_blank" rel="noopener noreferrer">
                <i class="fas fa-link"></i>
                
            </a>
        @else
            <span class="text-muted">
                @lang('main.not_found')
            </span>
        @endif
    </div>
    <hr>
</div>

{{-- Linkedin --}}
<div class="col-md-2 col-sm-12">
    <div class="h6">
        <span class="font-weight-bold">@lang("main.linkedin"): </span>
        @if (!! $company->linkedin)
            <a href="{{ $company->linkedin }}" class="btn btn-danger btn-sm" target="_blank" rel="noopener noreferrer">
                <i class="fas fa-link"></i>
                
            </a>
        @else
            <span class="text-muted">
                @lang('main.not_found')
            </span>
        @endif
    </div>
    <hr>
</div>

{{-- Instagram --}}
<div class="col-md-2 col-sm-12">
    <div class="h6">
        <span class="font-weight-bold">@lang("main.instagram"): </span>
        @if (!! $company->instagram)
            <a href="{{ $company->instagram }}" class="btn btn-danger btn-sm" target="_blank" rel="noopener noreferrer">
                <i class="fas fa-link"></i>
                
            </a>
        @else
            <span class="text-muted">
                @lang('main.not_found')
            </span>
        @endif
    </div>
    <hr>
</div>

{{-- Snapchat --}}
<div class="col-md-2 col-sm-12">
    <div class="h6">
        <span class="font-weight-bold">@lang("main.snapchat"): </span>
        @if (!! $company->snapchat)
            <a href="{{ $company->snapchat }}" class="btn btn-danger btn-sm" target="_blank" rel="noopener noreferrer">
                <i class="fas fa-link"></i>
                
            </a>
        @else
            <span class="text-muted">
                @lang('main.not_found')
            </span>
        @endif

    </div>
    <hr>
</div>

{{-- YouTube --}}
<div class="col-md-2 col-sm-12">
    <div class="h6">
        <span class="font-weight-bold">@lang("main.youtube"): </span>
        @if (!! $company->youtube)
            <a href="{{ $company->youtube }}" class="btn btn-danger btn-sm" target="_blank" rel="noopener noreferrer">
                <i class="fas fa-link"></i>
                
            </a>
        @else
            <span class="text-muted">
                @lang('main.not_found')
            </span>
        @endif
    </div>
    <hr>
</div>
