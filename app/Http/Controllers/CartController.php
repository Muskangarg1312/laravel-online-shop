<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\CustomerAddress;
use App\Models\DiscountCoupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ShippingCharge;
use Carbon\Carbon;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function addToCart(Request $request) {
        
        $product = Product::with('product_images')->find($request->id);
        
        if ($product == null) {
            return response()->json([
                'status' => false,
                'message' => 'Product not found'
            ]);
        }
        
        
        if (Cart::count() > 0) {
            
            // echo 'Product already in cart';
            // Products found in cart
            // Check if this product already exist in the cart
            // Return a message that product already added in your cart 
            // If product not found in the cart then add product in the cart 
            
            $cartContent = Cart::content();
            $productAlreadyExist = false;
            
            foreach ($cartContent as $item) {
                
                if ($item->id == $product->id) {
                    $productAlreadyExist = true;
                }
            }
            
            if ($productAlreadyExist == false) {
                Cart::add($product->id, $product->title, 1, $product->price, ['productImage' => (!empty($product->product_images)) ? $product->product_images->first() : '']);
                
                $status = true;
                $message = '<strong>'.$product->title.'</strong> added in cart successfully';
                session()->flash('success', $message);
            }
            else {
                $status = false;
                $message = $product->title.' already added in cart';
                // session()->flash('error', $message);
            }
        }
        
        else{
            
            Cart::add($product->id, $product->title, 1, $product->price, ['productImage' => (!empty($product->product_images)) ? $product->product_images->first() : '']);
            $status = true;
            $message = '<strong>'.$product->title.'</strong> added in cart successfully';   
            session()->flash('success', $message);
        }
        
        return response()->json([
            'status' => $status,
            'message' => $message
        ]);
        
    }
    
    public function cart() {
        // dd(Cart::content());
        
        $cartContent = Cart::content();
        $data['cartContent'] = $cartContent;
        return view('front.cart', $data);
    }
    
    public function updateCart(Request $request) {
        
        $rowId = $request->rowId;
        $qty = $request->qty;
        // dd($qty);
        // Check qty available in the stock
        
        $itemInfo = Cart::get($rowId);
        
        $product = Product::find($itemInfo->id);
        
        if ($product->track_qty == 'Yes') {
            if ($qty <= $product->qty) {
                Cart::update($rowId, $qty); 
                $status = true;
                $message = 'Cart updated successfully'; 
                session()->flash('success', $message);  
            }
            else {
                $status = false;
                $message = 'Requested qty ('.$qty.') not available in stock';
                session()->flash('error', $message);
            }    
        }
        else {
            Cart::update($rowId, $qty); 
            $status = true;
            $message = 'Cart updated successfully'; 
            session()->flash('success', $message);    
        }
        
        return response()->json([
            'status' => $status,
            'message' => $message
        ]);
    }
    
    public function deleteItem(Request $request) {
        
        $itemInfo = Cart::get($request->rowId);
        
        if ($itemInfo == null) {
            
            $error = 'Item not found in cart';
            
            session()->flash('error', $error);    
            return response()->json([
                'status' => false,
                'message' => $error
            ]);
            
        }
        
        Cart::remove($request->rowId);
        
        $message = 'Item removed from cart successfully';
        
        session()->flash('success', $message);    
        return response()->json([
            'status' => true,
            'message' => $message
        ]);
    }
    
    public function checkout() {
        
        $discount = 0;
        
        // If cart is empty redirect to cart page
        
        if (Cart::count() == 0) {
            return redirect()->route('front.cart');
        }
        
        // If user is not logged in redirect to login page
        
        if (Auth::check() == false) {
            
            // Saving current url in session url-intended 
            
            if (!session()->has('url-intended')) {
                session(['url-intended' => url()->current()]);
            }
            
            return redirect()->route('account.login');
        }
        
        $customerAddress = CustomerAddress::where('user_id', Auth::user()->id)->first();
        // dd($customerAddress);
        session()->forget('url-intended');
        
        $countries = Country::orderBy('name', 'asc')->get();
        
        // Calculate shipping here 
        // dd($customerAddress);
        
        $subTotal = Cart::subtotal(2,'.','');
        
        // Apply Discount here
        
        if (session()->has('code')) {
            $code = session()->get('code');
            $discount = 0; 
            
            if ($code->type == 'percent') {
                $discount = ($code->discount_amount)/100*$subTotal;
            }
            else { 
                $discount = $code->discount_amount;
            }
            
        }
        
        // $userCountry = $customerAddress->country_id;
        // $shippingInfo = ShippingCharge::where('country_id', $userCountry)->first();
        
        // Assuming $customerAddress, $subTotal, and $discount are defined elsewhere
        
        if ($customerAddress != '') {
            $userCountry = $customerAddress->country_id;
            $shippingInfo = ShippingCharge::where('country_id', $userCountry)->first();
            
            if ($shippingInfo !== null) {
                $totalQty = 0;
                $totalShippingCharge = 0;
                $grandTotal = 0;
                
                foreach (Cart::content() as $item) {
                    $totalQty += $item->qty;
                }
                
                $totalShippingCharge = $totalQty * $shippingInfo->amount;
                $grandTotal = ($subTotal - $discount) + $totalShippingCharge;
            } else {
                // Handle when shipping info is not found for the user's country
                // Example: Set default shipping charge or display a message
                $totalShippingCharge = 0; // Or set a default shipping charge
                $grandTotal = ($subTotal - $discount) + $totalShippingCharge;
                // You can add further logic or messages here based on your application's requirements
            }
        } else {
            $shippingInfo = ShippingCharge::where('country_id', 'rest_of_world')->first();
            
            if ($shippingInfo !== null) {
                $totalShippingCharge = 0;
                $grandTotal = ($subTotal - $discount) + $totalShippingCharge;
            } else {
                // Handle when shipping info is not found for 'rest_of_world'
                // Example: Set default shipping charge or display a message
                $totalShippingCharge = 0; // Or set a default shipping charge
                $grandTotal = ($subTotal - $discount) + $totalShippingCharge;
                // You can add further logic or messages here based on your application's requirements
            }
        }
        
        
        return view('front.checkout', [
            'countries' => $countries,
            'customerAddress' => $customerAddress,
            'totalShippingCharge' => $totalShippingCharge,
            'discount' => $discount,
            'grandTotal' => $grandTotal,
        ]);
    }
    
    public function processCheckout(Request $request) {
        
        // Step: 1 Apply Validation
        
        $validator = Validator::make($request->all(), [
            
            'first_name' => 'required|min:3',
            'last_name' => 'required',
            'email' => 'required|email',
            'country' => 'required',
            'address' => 'required|min:30',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required',
            'mobile' => 'required|numeric|min:10',
            
        ]);
        
        if ($validator->fails()) {
            
            return response()->json([
                'status' => false,
                'message' => 'Please fix the errors',
                'errors' =>$validator->errors()
            ]);
        }
        
        // Step: 2 Save User address
        
        $user = Auth::user();
        
        CustomerAddress::updateOrCreate(
            ['user_id' => $user->id],
            [
                'user_id' => $user->id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'country_id' => $request->country,
                'address' => $request->address,
                'apartment' => $request->apartment,
                'city' => $request->city,
                'state' => $request->state,
                'zip' => $request->zip,
                ]
            );
            
            // Step: 3 Store data in orders table
            
            if ($request->payment_method == 'cod') {
                
                // Calculate Shipping
                
                $discountCodeId = NULL;
                $promoCode = '';
                $shipping = 0;
                $discount = 0;
                $subTotal = Cart::subtotal(2, '.', ''); 
                
                // Apply Discount here
                
                if (session()->has('code')) {
                    $code = session()->get('code');
                    
                    if ($code->type == 'percent') {
                        $discount = ($code->discount_amount)/100*$subTotal;
                    }
                    else { 
                        $discount = $code->discount_amount;
                    }
                    $discountCodeId = $code->id;
                    $promoCode = $code->code;
                }
                
                $grandTotal = $subTotal+$shipping; 
                
                $shippingInfo = ShippingCharge::where('country_id', $request->country)->first();
                
                $totalQty = 0;
                foreach (Cart::content() as $item) {
                    $totalQty += $item->qty;
                }
                
                if ($shippingInfo != null) {
                    $shipping = $totalQty*$shippingInfo->amount;
                    $grandTotal = ($subTotal - $discount) + $shipping;
                }
                else {
                    $shippingInfo = ShippingCharge::where('country_id', 'rest_of_world')->first();
                    $shipping = $totalQty*$shippingInfo->amount;
                    $grandTotal = ($subTotal - $discount) + $shipping;
                }
                
                $order = new Order();
                $order->subtotal = $subTotal;
                $order->shipping = $shipping;
                $order->grand_total = $grandTotal;
                $order->discount = $discount;
                $order->coupon_code_id = $discountCodeId;
                $order->coupon_code	 = $promoCode;
                $order->payment_status = 'not paid';
                $order->status = 'pending';
                $order->user_id = $user->id;
                $order->first_name = $request->first_name;
                $order->last_name = $request->last_name;
                $order->email = $request->email;
                $order->mobile = $request->mobile;
                $order->country_id = $request->country;
                $order->address = $request->address;
                $order->apartment = $request->apartment;
                $order->city = $request->city;
                $order->state = $request->state;
                $order->zip = $request->zip;
                $order->notes = $request->notes;
                $order->save();
                
                // Step: 4 Store order items in order items table
                
                foreach (Cart::content() as $item) {
                    
                    $orderItem = new OrderItem();
                    $orderItem->product_id = $item->id;
                    $orderItem->order_id = $order->id;
                    $orderItem->name = $item->name;
                    $orderItem->qty = $item->qty;
                    $orderItem->price = $item->price;
                    $orderItem->total = $item->price*$item->qty;
                    // dd($orderItem);
                    $orderItem->save();

                    // Update Product Stock

                    $productData = Product::find($item->id);
                    if ($productData->track_qty == 'Yes') {
                        $currentQty = $productData->qty;
                        $updatedQty = $currentQty-$item->qty;
                        $productData->qty = $updatedQty;
                        $productData->save();
                    }
                   
                    
                }

                // Send Order Email to Customer

                orderEmail($order->id, 'customer');

                session()->flash('success', 'You have succesfully placed your order');
                Cart::destroy();
                session()->forget('code');
                
                return response()->json([
                    'status' => true,
                    'message' => 'Order saved successfully',
                    'orderId' => $order->id,
                    'errors' =>$validator->errors()
                ]);
                
            } else {
                session()->flash('error', 'Something went wrong, Please try again');
                return response()->json([
                    'status' => false,
                    'message' => 'Something went wrong',
                ]);
            }
            
        }
        
        public function thankyou($id) {
            return view('front.thanks', ['id' => $id]);
        }
        
        public function getOrderSummary(Request $request) {
            
            $subTotal = Cart::subtotal(2, '.', ''); 
            $discount = 0;
            $discountString = '';
            // Apply Discount here
            
            if (session()->has('code')) {
                $code = session()->get('code');
                
                if ($code->type == 'percent') {
                    $discount = ($code->discount_amount)/100*$subTotal;
                }
                else { 
                    $discount = $code->discount_amount;
                }
                $discountString = '<div class="mt-4" id="discount-response">
                <strong>' . $code->code . '</strong>
                <a class="btn btn-sm btn-danger" id="remove-discount"><i class="fa fa-times"></i></a>
                </div> '; 
            }
            
            if ($request->country_id > 0) {
                $shippingInfo = ShippingCharge::where('country_id', $request->country_id)->first();
                $totalQty = 0;
                foreach (Cart::content() as $item) {
                    $totalQty += $item->qty;
                }
                if ($shippingInfo != null) {
                    $shippingCharge = $totalQty*$shippingInfo->amount;
                    $grandTotal = ($subTotal - $discount) + $shippingCharge;
                    
                    return response()->json([
                        'status' => true,
                        'grandTotal' =>  number_format($grandTotal,2),
                        'discount' => number_format($discount,2),
                        'discountString' => $discountString,
                        'shippingCharge' =>  number_format($shippingCharge,2),
                    ]);
                    
                }
                else {
                    $shippingInfo = ShippingCharge::where('country_id', 'rest_of_world')->first();
                    
                    $shippingCharge = $totalQty*$shippingInfo->amount;
                    $grandTotal = ($subTotal - $discount) + $shippingCharge;
                    
                    return response()->json([
                        'status' => true,
                        'grandTotal' =>  number_format($grandTotal,2),
                        'discount' => number_format($discount,2),
                        'shippingCharge' =>  number_format($shippingCharge,2),
                    ]);
                }
                
            }
            else {
                return response()->json([
                    'status' => true,
                    'grandTotal' =>  number_format(($subTotal - $discount),2),
                    'discount' => number_format($discount,2),
                    'shippingCharge' =>  number_format(0,2),
                ]);
            }
        }
        
        public function applyDiscount(Request $request) {
            // dd($request->code);
            
            $code = DiscountCoupon::where('code', $request->code)->first();
            
            if ($code == null) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid Coupon',
                ]);
            }
            
            // Check if start date is valid or not
            
            $now = Carbon::now();
            
            // echo 'NOW - '. $now->format('Y-m-d H:i:s');
            
            if ($code->starts_at != "") {
                $startDate = Carbon::createFromFormat('Y-m-d H:i:s', $code->starts_at);
                // echo '<br>';
                // echo  'START AT - '.$startDate;
                if ($now->lt($startDate)) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Coupon Not Yet Active',
                    ]);
                }
            }
            
            if ($code->expires_at != "") {
                $endDate = Carbon::createFromFormat('Y-m-d H:i:s', $code->expires_at);
                
                if ($now->gt($endDate)) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Coupon has Expired',
                    ]);
                }
            }
            
            // Max. Uses Check
            
            if ($code->max_uses > 0) {
                $couponUsed = Order::where('coupon_code_id', $code->id)->count();
                
                if ($couponUsed >= $code->max_uses) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Coupon limit reached. Try another.',
                    ]);
                }
            }
            
            
            // Max. Uses User Check
            
            if ($code->max_uses_user > 0) {
                $couponUsedByUser = Order::where(['coupon_code_id'=> $code->id, 'user_id' => Auth::user()->id])->count();
                
                if ($couponUsedByUser >= $code->max_uses_user) {
                    return response()->json([
                        'status' => false,
                        'message' => 'You have already used this coupon',
                    ]);
                }
            }
            
            // Min amount condition
            
            $subTotal = Cart::subtotal(2, '.', ''); 
            
            if ($code->min_amount > 0) {
                
                if ($subTotal < $code->min_amount) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Your min. amount must be '.$code->min_amount.'.',
                    ]);
                }
            }
            
            session()->put('code', $code);
            
            // Calling getOrderSummary function to calculate new charges and also apply discount on it
            
            return $this->getOrderSummary($request);
        }
        
        public function removeCoupon(Request $request) {
            session()->forget('code');
            return $this->getOrderSummary($request);
        }
    }
    
    