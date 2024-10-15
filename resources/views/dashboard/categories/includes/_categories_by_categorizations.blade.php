 <option value="" disabled selected>@lang('main.category')</option>
 @foreach ($categories as $category)
     <option {{ $category->id == old('categories_id') ? 'selected' : '' }} value="{{ $category->id }}">
         {{ $category->name_by_lang }}
     </option>
 @endforeach
