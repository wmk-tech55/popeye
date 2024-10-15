@extends('dashboard.layouts.app')

@section('section', $sectionName)

@section('styles')
    <link rel="stylesheet" href="{{asset('/dashboard_assets/datatables/datatables.min'. getRtlDirection() .'.css')}}">
@endsection

@section('content')
 
  
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $sectionName }} : {{$name}}</h1>
        
        <a href="{{route('dashboard.companies.show',request()->company )}}" class="d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-store fa-sm text-white-50"></i>
            @lang('main.show') @lang('main.profile')
        </a>    
    </div>

    <div class="row">
        <div class="col-xl-12 col-md-12 col-sm-12">
                <div class="card">
                    <div class="card-header">
                        {{ $sectionName }}
                    </div>
                    <div class="card-body">
                       
                            {!! $dataTable->table(['class'=> 'table table-striped table-bordered table-hover'], true) !!}
                            
                        
                    </div>
                </div>
        </div>
    </div>

    
    
@endsection

@section('scripts')
    <script src="{{asset('/dashboard_assets/datatables/datatables.min.js')}}"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $(window).keydown(function(event){
                if(event.keyCode == 13) {
                    event.preventDefault();
                    return false;
                }
            });
        });
        
        $(document).on('click', '.createBtn', function() {
            window.location = "{{ route('dashboard.companies.create') }}";
        });

        $(document).on('click', '.deleteBtn', function() {
            $('#multi_delete').modal('show');
            var number_checkbox = $(".selected_data").filter(":checked").length;
            $('#count').html(number_checkbox);
            if(number_checkbox > 0){
                $('.delete_done').show();
                $('.check_delete').hide();
            }else{
                $('.delete_done').hide();
                $('.check_delete').show();
            }
        });
    </script>
    {!! $dataTable->scripts() !!}
@endsection
