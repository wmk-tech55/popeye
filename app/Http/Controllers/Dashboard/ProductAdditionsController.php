<?php

namespace MixCode\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use MixCode\Http\Controllers\Controller;
use MixCode\ProductAddition;

class ProductAdditionsController extends Controller
{

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
        
        $productAddition->update($request->all());

        notify('success', trans('main.updated'));

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \MixCode\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $productAddition = ProductAddition::find($id);

        $productAddition->delete();

        notify('success', trans('main.deleted-message'));

        return redirect()->back();
    }
}
