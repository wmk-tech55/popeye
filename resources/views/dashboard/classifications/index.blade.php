@extends('dashboard.layouts.app')

@section('section', $sectionName)

@section('styles')
<link rel="stylesheet" href="{{asset('/dashboard_assets/datatables/datatables.min'. getRtlDirection() .'.css')}}">
@endsection

@section('content')


<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <a href="{{ route('dashboard.classifications.create') }}"
            class="d-sm-inline-block btn btn-sm btn-success shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i>
            @lang('main.add') @lang('main.classifications')
        </a>
        <a href="{{ route('dashboard.classifications.trashed') }}"
            class="d-sm-inline-block btn btn-sm btn-danger shadow-sm">
            <i class="fas fa-trash fa-sm text-white-50"></i>
            @lang('main.trashed') @lang('main.classifications')
        </a>
    </div>
</div>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">{{ $sectionName }}</h1>
</div>

<div class="row">
    <div class="col-xl-12 col-md-12 col-sm-12">
        <div class="card">

            
            @include('dashboard.classifications.includes._filter_with_categorization' , ['categorizations'=>$categorizations])

            <div class="card-header">
                {{ $sectionName }}
            </div>

            <div class="card-body table-responsive">
                <form method="post" action="{{ route('dashboard.classifications.destroyGroup') }}">
                    @csrf
                    @method('DELETE')

                    {!! $dataTable->table(['class'=> 'table table-striped table-bordered table-hover'], true) !!}
                    @include('dashboard.layouts.includes.multiDeleteModelForm')
                </form>
            </div>
        </div>
    </div>
</div>



@endsection

@section('scripts')
<script src="{{asset('/dashboard_assets/datatables/datatables.min.js')}}"></script>

<script>
    $('#categorization_id').on('change', function() {
                    var   categorization_id     = $('#categorization_id').val();
        
                    if(categorization_id == null){
                        categorization_id = '';
                    }
                    window.location = "{{ route('dashboard.classifications.index') }}"+ '?categorization_id='+ categorization_id ;
                });
                
                $('#filter_clear').on('click', function() { 
                    window.location = "{{ route('dashboard.classifications.index') }}";
                });
</script>


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
            window.location = "{{ route('dashboard.classifications.create') }}";
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