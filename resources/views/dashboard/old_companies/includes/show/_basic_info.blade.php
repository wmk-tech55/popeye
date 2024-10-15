 {{-- Full Name --}}
 <div class="col-md-4 col-sm-12">
     <div class="h6">
         <span class="font-weight-bold">@lang('main.full_name'): </span>

         <span class="text-muted">{{ $company->full_name }}</span>
     </div>
     <hr>
 </div>



 {{-- Email --}}
 <div class="col-md-4 col-sm-12">
     <div class="h6">
         <span class="font-weight-bold">@lang('main.email'): </span>

         <span class="text-muted">{{ $company->email }}</span>

     </div>
     <hr>
 </div>

 @if ($company->isCompany())
     {{-- username --}}
     <div class="col-md-4 col-sm-12">
         <div class="h6">
             <span class="font-weight-bold">@lang('main.username'): </span>

             <span class="text-muted">{{ $company->username }}</span>
         </div>
         <hr>
     </div>
 @endif

 {{-- Phone --}}
 <div class="col-md-4 col-sm-12">
     <div class="h6">
         <span class="font-weight-bold">@lang('main.phone'): </span>

         <span class="text-muted">{{ $company->phone }}</span>

     </div>
     <hr>
 </div>
 
 {{-- country --}}
 @if ($company->country)
     
 <div class="col-md-4 col-sm-12">
     <div class="h6">
         <span class="font-weight-bold">@lang('main.country'): </span>

         <span class="text-muted">{{ $company->country ?? trans('main.not_found') }}</span>

     </div>
     <hr>
 </div>
 @endif


 {{-- city --}}
@if ($company->city)
    
<div class="col-md-4 col-sm-12">
    <div class="h6">
        <span class="font-weight-bold">@lang('main.city'): </span>

        <span class="text-muted">{{ $company->city ?? trans('main.not_found') }}</span>

    </div>
    <hr>
</div>
@endif
 {{-- area --}}
 @if ($company->area)
     <div class="col-md-4 col-sm-12">
         <div class="h6">
             <span class="font-weight-bold">@lang('main.area'): </span>

             <span class="text-muted">{{ $company->area ?? trans('main.not_found') }}</span>

         </div>
         <hr>
     </div>
 @endif

 {{-- address --}}
 @if ($company->address)
     
 <div class="col-md-12 col-sm-12">
     <div class="h6">
         <span class="font-weight-bold">@lang('main.address'): </span>

         <span class="text-muted">{{ $company->address ?? trans('main.not_found') }}</span>

     </div>
     <hr>
 </div>
 @endif

 @if ($company->isCompany())
     <div class="col-md-6 col-sm-12">
         <div class="h6">
             <span class="font-weight-bold">@lang('main.categorization'): </span>

             <span class="text-muted">{{ $company->categorization->name_by_lang }}</span>
         </div>
         <hr>
     </div>
 @endif


 {{-- Status --}}
 <div class="col-md-6 col-sm-12">
     <div class="h6">
         <span class="font-weight-bold">@lang('main.status'): </span>

         @if ($company->isActive())
             <span class="badge badge-success">@lang('main.active')</span>
         @elseif($company->isPending())
             <span class="badge badge-warning">@lang('main.pending')</span>
         @else
             <span class="badge badge-danger">@lang('main.disabled')</span>
         @endif
     </div>
     <hr>
 </div>
