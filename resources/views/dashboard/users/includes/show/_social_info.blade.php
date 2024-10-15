{{-- Facebook --}}
<div class="col-md-6 col-sm-12">
    <div class="h6">
        <span class="font-weight-bold">@lang("main.facebook"): </span>
        @if (!! $user->facebook)
            <a href="{{ $user->facebook }}" class="btn btn-danger btn-sm" target="_blank" rel="noopener noreferrer">
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
        @if (!! $user->twitter)
            <a href="{{ $user->twitter }}" class="btn btn-danger btn-sm" target="_blank" rel="noopener noreferrer">
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
        @if (!! $user->linkedin)
            <a href="{{ $user->linkedin }}" class="btn btn-danger btn-sm" target="_blank" rel="noopener noreferrer">
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
        @if (!! $user->instagram)
            <a href="{{ $user->instagram }}" class="btn btn-danger btn-sm" target="_blank" rel="noopener noreferrer">
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
        @if (!! $user->snapchat)
            <a href="{{ $user->snapchat }}" class="btn btn-danger btn-sm" target="_blank" rel="noopener noreferrer">
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
        @if (!! $user->youtube)
            <a href="{{ $user->youtube }}" class="btn btn-danger btn-sm" target="_blank" rel="noopener noreferrer">
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
