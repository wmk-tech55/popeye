<?php

namespace MixCode\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use MixCode\Address;
use MixCode\Http\Controllers\Controller;
use MixCode\Http\Requests\AddressesRequest;
use MixCode\Http\Requests\AddressRequest;
use MoaAlaa\ApiResponder\ApiResponder;

class AddressesController extends Controller
{
    use ApiResponder;


    public function index()
    {
        $address = Address::where('owner_id', auth()->id())->get();

        if (!$address) {
            return $this->api()->error(trans('main.not_found'), Response::HTTP_NOT_FOUND);
        }

        return $this->api()->response($address, null, Response::HTTP_CREATED);
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddressRequest $request, Address  $address)
    {
 
        $address =  $address->createNew($request);

        if ($address) {
            return $this->api()->response($address, null, Response::HTTP_CREATED);
        } else {
            return $this->api()->response([], trans('site.cant_add_more_than_five_address'), Response::HTTP_NOT_ACCEPTABLE);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \MixCode\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function update(Address $address, Request $request)
    {
        // $address = Address::find($request->address_id);

        abort_unless($address, Response::HTTP_NOT_FOUND, trans('main.not_found'));

        $address->update($request->all());

        return $this->api()->response($address, null, Response::HTTP_CREATED);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \MixCode\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function markAsDefault(Address $address)
    {

        abort_unless($address, Response::HTTP_NOT_FOUND, trans('site.no_data_available'));

        auth()->user()->addresses()->update(['is_default' => false]);

        auth()->user()->update([
            'longitude' => $address->longitude,
            'latitude'  => $address->latitude,
            'zoom'      => $address->zoom,
        ]);

        $address->update(['is_default' => true]);

        return $this->api()->response($address, null, Response::HTTP_CREATED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \MixCode\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function destroy($address_id)
    {
        $address = Address::find($address_id);
        $address->delete();

        return $this->api()->response(trans('main.deleted-message'), null, Response::HTTP_CREATED);
    }
}
