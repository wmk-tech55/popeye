<?php

namespace MixCode\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use MixCode\Http\Controllers\Controller;
use MixCode\Product;
use MixCode\ProductAddition;
use MoaAlaa\ApiResponder\ApiResponder;


class ProductAdditionsController extends Controller
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
        $productAddition = ProductAddition::find($id);

        $request->validate([
            'ar_name' => ['required', 'string',],
            'en_name' => ['required', 'string',],
            'price'   => ['required', 'numeric'],
        ]);


        $data           = resolveDataValues($request->all());
        $productAddition->update($data);

        notify('success', trans('main.updated'));

        return $this->api()->response($productAddition, trans('main.updated'), Response::HTTP_OK);
    }

    /**
     *  Bulk Update  
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \MixCode\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function bulkUpdate(ProductAddition $productAddition, Request $request)
    {

        $productAddition->updateBulk($request);

        notify('success', trans('main.updated'));

        return $this->api()->response([], trans('main.updated'), Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \MixCode\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $productAddition = ProductAddition::find($id);

        $productAddition->delete();

        return $this->api()->response([], trans('main.deleted-message'), Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \MixCode\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function bulkDelete(ProductAddition $productAddition, Request $request)
    {
        
        $productAddition->deleteBulk($request);

        return $this->api()->response([], trans('main.deleted-message'), Response::HTTP_OK);
    }
}
