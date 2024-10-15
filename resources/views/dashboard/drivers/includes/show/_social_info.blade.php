{{-- Facebook --}}
<div class="col-md-6 col-sm-12">
    <div class="h6">
        <span class="font-weight-bold">@lang("main.facebook"): </span>
        @if (!! $driver->facebook)
            <a href="{{ $driver->facebook }}" class="btn btn-danger btn-sm" target="_blank" rel="noopener noreferrer">
                <i class="fas fa-link"></i>
                @lang('main.visit_url')
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
<div class="col-md-6 col-sm-12">
    <div class="h6">
        <span class="font-weight-bold">@lang("main.twitter"): </span>
        @if (!! $driver->twitter)
            <a href="{{ $driver->twitter }}" class="btn btn-danger btn-sm" target="_blank" rel="noopener noreferrer">
                <i class="fas fa-link"></i>
                @lang('main.visit_url')
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
<div class="col-md-6 col-sm-12">
    <div class="h6">
        <span class="font-weight-bold">@lang("main.linkedin"): </span>
        @if (!! $driver->linkedin)
            <a href="{{ $driver->linkedin }}" class="btn btn-danger btn-sm" target="_blank" rel="noopener noreferrer">
                <i class="fas fa-link"></i>
                @lang('main.visit_url')
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
<div class="col-md-6 col-sm-12">
    <div class="h6">
        <span class="font-weight-bold">@lang("main.instagram"): </span>
        @if (!! $driver->instagram)
            <a href="{{ $driver->instagram }}" class="btn btn-danger btn-sm" target="_blank" rel="noopener noreferrer">
                <i class="fas fa-link"></i>
                @lang('main.visit_url')
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
<div class="col-md-6 col-sm-12">
    <div class="h6">
        <span class="font-weight-bold">@lang("main.snapchat"): </span>
        @if (!! $driver->snapchat)
            <a href="{{ $driver->snapchat }}" class="btn btn-danger btn-sm" target="_blank" rel="noopener noreferrer">
                <i class="fas fa-link"></i>
                @lang('main.visit_url')
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
<div class="col-md-6 col-sm-12">
    <div class="h6">
        <span class="font-weight-bold">@lang("main.youtube"): </span>
        @if (!! $driver->youtube)
            <a href="{{ $driver->youtube }}" class="btn btn-danger btn-sm" target="_blank" rel="noopener noreferrer">
                <i class="fas fa-link"></i>
                @lang('main.visit_url')
            </a>
        @else
            <span class="text-muted">
                @lang('main.not_found')
            </span>
        @endif
    </div>
    <hr>
</div>
