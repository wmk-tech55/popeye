
 
 @foreach ($products as $product)

 <option value="{{ $product->id }}" >
     {{ $product->name_by_lang }} 
 </option>
 @endforeach