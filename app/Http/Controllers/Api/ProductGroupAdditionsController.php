<?php

namespace MixCode\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use MixCode\Category;
use MixCode\Http\Controllers\Controller;
use MixCode\ProductGroupAddition;
use MoaAlaa\ApiResponder\ApiResponder;

class ProductGroupAdditionsController extends Controller
{
    use ApiResponder;
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \MixCode\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $id)
    {
        $productGroupAddition = ProductGroupAddition::find($id);

        $request->validate([
            'ar_name' => ['required', 'string',],
            'type'    => ['required', 'string'],
        ]);

        $data           = resolveDataValues($request->all());
        $productGroupAddition->update($data);
  
        notify('success', trans('main.updated'));
 
        return $this->api()->response($productGroupAddition, trans('main.updated'), Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \MixCode\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $productGroupAddition = ProductGroupAddition::find($id);

        $productGroupAddition->delete();

        return $this->api()->response([], trans('main.deleted-message'), Response::HTTP_OK);
    }
}
