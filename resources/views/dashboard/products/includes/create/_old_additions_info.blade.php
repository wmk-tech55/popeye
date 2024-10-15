

 {{-- Additions --}}
 <div class="form-group mb-4 row  d-none additions-options-section">

     <div class="col-sm-10">

         <div id="newAdditionGroupRowContainer">

             <div id="accordionExample" class="  mt-4" data-id="other-classifications">
                 <div class="card">
                     <div class="card-header" style="background-color: #e0e0e0; " id="headingOne-{{ time() }}">
                         <h2 class="mb-0">
                             <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                                 data-target="#collapseOne-{{ time() }}" aria-expanded="true"
                                 aria-controls="collapseOne-{{ time() }}">
                                 1 @lang('main.group')
                             </button>
                         </h2>
                         <span id="section-title">
                             <div class="row">
                                 <div class="form-group col-md-5 col-sm-5  ">

                                     <input type="text" name="group_ar_names[]" id="group_ar_names"
                                         class="form-control @error('group_ar_names') is-invalid @enderror"
                                         placeholder="@lang('main.group_name')" required>
                                     @error('group_ar_names')
                                         <div class="invalid-feedback">
                                             {{ $message }}
                                         </div>
                                     @enderror

                                 </div>
                                 {{-- group_type --}}
                                 <div class="form-group col-md-4 col-sm-4  ">

                                     <select name="group_types[]"
                                         class="form-control group-type select2 @error('group_types') is-invalid @enderror"
                                         data-placeholder="@lang('main.type')" required>
                                         <option value="" disabled selected>@lang('main.type')</option>
                                         @foreach ($groupTypes as $groupType)
                                             <option value="{{ $groupType }}">
                                                 @lang('main.' . $groupType)
                                             </option>
                                         @endforeach

                                     </select>
                                     @error('categorization_id')
                                         <div class="invalid-feedback">
                                             {{ $message }}
                                         </div>
                                     @enderror


                                 </div>
                                 <div class="form-group col-md-3 col-sm-3 ">

                                     <a href="#" data-toggle="modal" data-target="#groupTypes">
                                         <i class="fas fa-exclamation-circle"></i> @lang('main.group_types')
                                     </a>
                                 </div>
                             </div>
                         </span>
                     </div>

                     <div id="collapseOne-{{ time() }}" class="collapse show"
                         style="background-color:#fffcfc; box-shadow:#e2e2e2 1px 4px 5px 1px;"
                         aria-labelledby="headingOne-{{ time() }}" data-parent="#accordionExample">
                         <div class="card-body">

                             <div class="special-price">
                                 <label class=" col-form-label">@lang('main.additions') : </label>
                                 <div id="newAdditionRowContainer">


                                     @if ($errors->has('additions_prices.*') || $errors->has('additions_ar_names.*'))
 
                                         @for ($i = 0; $i < count(old('additions_prices')); $i++)
                                             <div class="additions-container row">
                                                 <div class="col-sm-5 ">
                                                     <input type="text" name="additions_ar_names[0][]" required
                                                         value="{{ old('additions_ar_names')[0][$i] ?? '' }}"
                                                         class="form-control mb-1 special-price-inputs @error("additions_ar_names.0.{$i}") is-invalid @enderror"
                                                         placeholder="@lang('main.name') *">
                                                     @error("additions_ar_names.0.{$i}")
                                                         <div class="invalid-feedback">
                                                             {{ $message }}
                                                         </div>
                                                     @enderror
                                                 </div>

                                                 <div class="col-sm-5 ">
                                                     <input type="number" name="additions_prices[0][]" required
                                                         value="{{ isset(old('additions_prices')[0][$i]) ? old('additions_prices')[0][$i] : '' }}"
                                                         class="form-control mb-1 special-price-inputs @error("additions_prices.0.{$i}") is-invalid @enderror"
                                                         placeholder="@lang('main.price') *">
                                                     @error("additions_prices.0.{$i}")
                                                         <div class="invalid-feedback">
                                                             {{ $message }}
                                                         </div>
                                                     @enderror
                                                 </div>
                                             </div>
                                         @endfor
                                     @else
                                         <div class="additions-container row">
                                             <div class="col-sm-5 ">
                                                 <input type="text" name="additions_ar_names[0][]" required
                                                     value="{{ old('additions_ar_names.0.0') }}"
                                                     class="form-control mb-1 special-price-inputs @error('additions_ar_names.0.0') is-invalid @enderror"
                                                     placeholder="@lang('main.name') *">
                                                 @error('additions_ar_names.0.0')
                                                     <div class="invalid-feedback">
                                                         {{ $message }}
                                                     </div>
                                                 @enderror
                                             </div>

                                             <div class="col-sm-5 ">
                                                 <input type="number" name="additions_prices[0][]" required
                                                     value="{{ old('additions_prices.0.0') }}"
                                                     class="form-control mb-1 special-price-inputs @error('additions_prices.0.0') is-invalid @enderror"
                                                     placeholder="@lang('main.price') *">
                                                 @error('additions_prices.0.0')
                                                     <div class="invalid-feedback">
                                                         {{ $message }}
                                                     </div>
                                                 @enderror
                                             </div>
                                         </div>
                                     @endif
                                 </div>

                                 <button id="newAdditionRow" type="button" data-addition-index='0'
                                     class="newAdditionRow btn btn-success mb-1 mt-1 btn-sm">
                                     <i class="fas fa-plus"></i>
                                 </button>


                             </div>

                         </div>
                     </div>
                 </div>

             </div>

         </div>
         <button id="newAdditionGroupRow" type="button" class="btn btn-primary mb-1 mt-3 btn-sm bt-3">
             <i class="fas fa-plus"></i>
             @lang('main.add_group')
         </button>

         @error('additions')
             <div class="invalid-feedback">
                 {{ $message }}
             </div>
         @enderror
     </div>

 </div>
