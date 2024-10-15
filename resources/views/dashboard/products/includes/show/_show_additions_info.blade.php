<div class="card  border-left-danger pb-5">


    <div class="card-title font-weight-bold h5 text-center  text-danger m-3">@lang('main.additions')</div>
    <div class="col-md-12">
        <div id="accordion">

            <div class="card row">

                <div class="col-md-12 col-lg-12 col-sm-12">
                    <table class="table table-bordered table-hover mb-3">
 
                        @forelse ($product->productAdditions as $addition)
                            <tr>
                                <td>{{ $addition->name_by_lang }}</td>
                                <td>{{ $addition->price }} {{ currency() }}</td>
                                <td>
                                    <a href="#" class="d-sm-inline-block btn btn-sm btn-info shadow-sm"
                                        data-toggle="modal" data-target="#editProductAddition-{{ $addition->id }}"
                                        title="@lang('main.edit')">
                                        <i class="fas fa-edit fa-sm  "></i>

                                    </a>

                                    @component('dashboard.components.updateProductAdditionModelForm')
                                        @slot('addition', $addition)
                                        @slot('url', route('dashboard.additions.update', $addition))
                                    @endcomponent

                                    <a href="{{ route('dashboard.additions.destroy', $addition) }}"
                                        class="d-sm-inline-block btn btn-sm btn-danger shadow-sm" data-toggle="modal"
                                        data-target="#deleteModel-{{ $addition->id }}" title="@lang('main.delete')">
                                        <i class="fas fa-trash fa-sm  "></i>
                                    </a>

                                    @component('dashboard.components.deleteModelForm')
                                        @slot('id', $addition->id)
                                        @slot('deleteTitle', trans('main.addition') . ' ' . $addition->name_by_lang)
                                        @slot('url', route('dashboard.additions.destroy', $addition->id))
                                    @endcomponent
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="2">@lang('main.not_found')</td>

                                </tr>
                            @endforelse

                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
