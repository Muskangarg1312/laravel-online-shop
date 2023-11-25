<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\ShippingCharge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShippingController extends Controller
{
    public function create() {
        $countries = Country::get();
        $data['countries'] = $countries;
        $shippingCharges = ShippingCharge::select('shipping_charges.*', 'countries.name')
                                        ->leftJoin('countries', 'countries.id', 'shipping_charges.country_id')->get();
        $data['shippingCharges'] = $shippingCharges;
        return view('admin.shipping.create', $data);

    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'country' => 'required',
            'amount' => 'required|numeric'
        ]);

        if ($validator->passes()) {

            $count = ShippingCharge::where('country_id', $request->country)->count();
            
            if ($count > 0) {

                $request->session()->flash('error', 'Shipping already exists');

                return response()->json([
                    'status' => true,
                ]); 
            }

            $shipping = new ShippingCharge();
            $shipping->country_id = $request->country;
            $shipping->amount = $request->amount;
            $shipping->save();

            $request->session()->flash('success', 'Shipping added successfully');

            return response()->json([
                'status' => true,
                'message' => 'Shipping added successfully'
            ]);
        }
        else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function edit($id, Request $request) {
        $shippingCharge = ShippingCharge::find($id);

        if( !$shippingCharge ){

            $request->session()->flash('error', 'Shipping not found');
            return redirect()->route('shipping.create');

        }

        $countries = Country::get();
        $data['countries'] = $countries;
        // $shippingCharge = ShippingCharge::select('shipping_charges.*', 'countries.name')
        //                                 ->leftJoin('countries', 'countries.id', 'shipping_charges.country_id')->get();
        $data['shippingCharge'] = $shippingCharge;
        // dd($data);
        return view('admin.shipping.edit', $data);
    }

    public function update($id, Request $request) {
        
        $shipping = ShippingCharge::find($id);

        if( !$shipping ){

            $request->session()->flash('error', 'Shipping not found');
            return response()->json([
                'status' => true,
                'notFound' => true,
                'message' => 'Shipping not found'
            ]);

        }

        $validator = Validator::make($request->all(), [
            'country' => 'required',
            'amount' => 'required|numeric'
        ]);

        if ($validator->passes()) {


            $shipping->country_id = $request->country;
            $shipping->amount = $request->amount;
            $shipping->save();

            $request->session()->flash('success', 'Shipping updated successfully');

            return response()->json([
                'status' => true,
                'message' => 'Shipping updated successfully'
            ]);
        }
        else {
            return response()->json([
                'status' => true,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function destroy($id, Request $request) {

        $shippingCharge = ShippingCharge::find($id);

        if( !$shippingCharge ){

            $request->session()->flash('error', 'Shipping not found');
            return response()->json([
                'status' => true,
                'notFound' => true,
                'message' => 'Shipping not found'
            ]);

        }

        $shippingCharge->delete();

        $request->session()->flash('success', 'Shipping deleted successfully');

            return response()->json([
                'status' => true,
                'message' => 'Shipping deleted successfully'
            ]);
    }
}
