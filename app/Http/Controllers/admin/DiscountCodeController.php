<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\DiscountCoupon;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class DiscountCodeController extends Controller
{
    public function index(Request $request) {
        
        $discountCoupons = DiscountCoupon::latest();
        
        if (!empty($request->get('keyword'))) {
            $discountCoupons = $discountCoupons->where('name', 'like', '%'.$request->get('keyword').'%');
            $discountCoupons = $discountCoupons->orWhere('code', 'like', '%'.$request->get('keyword').'%');
        }        
        
        $discountCoupons = $discountCoupons->paginate(10);
        
        //dd($discountCoupons);
        $data['discountCoupons'] = $discountCoupons;
        
        return view('admin.coupon.list', $data);
    }
    
    public function create() {
        return view('admin.coupon.create');
    }
    
    public function store(Request $request) {
        
        $validator = Validator::make($request->all(), [
            'code' => 'required',
            'type' => 'required',
            'discount_amount' => 'required|numeric',
            'status' => 'required',
        ]);
        
        if($validator->passes()){
            
            // Starting date must be greater than current date
            
            if (!empty($request->starts_at)) {
                
                $now = Carbon::now(); // Current Date and Time
                
                $startAt = Carbon::createFromFormat('Y-m-d H:i:s', $request->starts_at);
                
                // lte => less than equal
                
                if ($startAt->lte($now) == true) {
                    return response()->json([
                        'status' => false,
                        'errors' => ['starts_at' => 'Start Date cannot be less than current date time']
                    ]);
                }
            }
            
            // Expiry date must be greater than starting date
            
            if (!empty($request->starts_at) && !empty($request->expires_at)) {
                
                $now = Carbon::now(); // Current Date and Time
                
                $startAt = Carbon::createFromFormat('Y-m-d H:i:s', $request->starts_at);
                $expiresAt = Carbon::createFromFormat('Y-m-d H:i:s', $request->expires_at);
                
                // gt => greater than
                
                if ($expiresAt->gt($startAt) == false) {
                    return response()->json([
                        'status' => false,
                        'errors' => ['expires_at' => 'Expiry Date must be greater than Start date']
                    ]);
                }
            }
            
            $discountCode = new DiscountCoupon();
            $discountCode->code = $request->code;
            $discountCode->name = $request->name;
            $discountCode->description = $request->description;
            $discountCode->max_uses = $request->max_uses;
            $discountCode->max_uses_user = $request->max_uses_user;
            $discountCode->type = $request->type;
            $discountCode->discount_amount = $request->discount_amount;
            $discountCode->min_amount = $request->min_amount;
            $discountCode->status = $request->status;
            $discountCode->starts_at = $request->starts_at;
            $discountCode->expires_at = $request->expires_at;
            $discountCode->save();
            
            $request->session()->flash('success', 'Discount Coupon added successfully');
            
            return response()->json([
                'status' => true,
                'message' => 'Discount Coupon added successfully'
            ]);
            
        }
        else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    
    public function edit($id, Request $request) {
        // echo $id;
        $data = [];
        $coupon = DiscountCoupon::find($id);
        
        if ($coupon ==null) {
            $request->session()->flash('error', 'Discount Coupon not found');
            return redirect()->route('coupons.index');
        }
        
        $data['coupon'] = $coupon;
        
        return view('admin.coupon.edit', $data);
    }
    
    public function update($id, Request $request) {
        
        $discountCode = DiscountCoupon::find($id);
        
        if( !$discountCode ){
            $request->session()->flash('error', 'Discount Coupon not found');
            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'Discount Coupon not found'
            ]);
        }
        
        $validator = Validator::make($request->all(), [
            'code' => 'required',
            'type' => 'required',
            'discount_amount' => 'required|numeric',
            'status' => 'required',
        ]);
        
        if($validator->passes()){
            
            // Expiry date must be greater than starting date
            
            if (!empty($request->starts_at) && !empty($request->expires_at)) {
                
                $now = Carbon::now(); // Current Date and Time
                
                $startAt = Carbon::createFromFormat('Y-m-d H:i:s', $request->starts_at);
                $expiresAt = Carbon::createFromFormat('Y-m-d H:i:s', $request->expires_at);
                
                // gt => greater than
                
                if ($expiresAt->gt($startAt) == false) {
                    return response()->json([
                        'status' => false,
                        'errors' => ['expires_at' => 'Expiry Date must be greater than Start date']
                    ]);
                }
            }
            
            $discountCode->code = $request->code;
            $discountCode->name = $request->name;
            $discountCode->description = $request->description;
            $discountCode->max_uses = $request->max_uses;
            $discountCode->max_uses_user = $request->max_uses_user;
            $discountCode->type = $request->type;
            $discountCode->discount_amount = $request->discount_amount;
            $discountCode->min_amount = $request->min_amount;
            $discountCode->status = $request->status;
            $discountCode->starts_at = $request->starts_at;
            $discountCode->expires_at = $request->expires_at;
            $discountCode->save();
            
            $request->session()->flash('success', 'Discount Coupon updated successfully');
            
            return response()->json([
                'status' => true,
                'message' => 'Discount Coupon updated successfully'
            ]);
            
        }
        else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    
    public function destroy($id, Request $request) {
        
        $discountCode = DiscountCoupon::find($id);
        
        if( !$discountCode ){
            $request->session()->flash('error', 'Discount Coupon not found');
            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'Discount Coupon not found'
            ]);
        }

        $discountCode->delete();
  
        $request->session()->flash('success', 'Discount Coupon deleted successfully');
            return response()->json([
                'status' => true,
                'message' => 'Discount Coupon deleted successfully'
            ]);
    }
}
