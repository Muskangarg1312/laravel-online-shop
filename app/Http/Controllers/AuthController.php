<?php

namespace App\Http\Controllers;

use App\Mail\ResetPasswordEmail;
use App\Models\Country;
use App\Models\CustomerAddress;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login() {
        return view('front.account.login');
    }
    
    public function register() {
        return view('front.account.register');
    }
    
    public function processRegister(Request $request) {
        
        $validator = Validator::make($request->all(), [
            
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'phone' => 'numeric|digits:10',
            'password' => 'required|min:5|confirmed',
            
        ]);  
        
        if ($validator->passes()) {
            
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->password = $request->password;
            // $user->password = Hash::make($request->password);
            $user->save();
            
            $request->session()->flash('success', 'You have been registered successfully');
            
            return response()->json([
                'status' => true,
                'message' => 'You have been registered successfully'
            ]);
            
        } 
        else {
            return response()->json([
                
                'status' => false,
                'errors' => $validator->errors()
                
            ]);
        }
        
    }
    
    public function authenticate(Request $request) {
        
        $validator = Validator::make($request->all(), [
            
            'email' => 'required|email',
            'password' => 'required',
            
        ]); 
        
        if ($validator->passes()) {
            
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->get('remember'))) {
                
                // If logged in redirect back to the saved session url-intended 
                
                if (session()->has('url-intended')) {
                    return redirect(session()->get('url-intended'));
                }
                
                return redirect()->route('account.profile')->with('success', 'Logged In successfully');
                
            }
            else {
                
                return redirect()->route('account.login')->with('error', 'Either email or password is Incorrect')->withInput($request->only('email'));
            }
            
        }
        else {
            return redirect()->route('account.login')
            ->withErrors($validator)
            ->withInput($request->only('email'));
        }
    }
    
    public function profile() {
        $userId = Auth::user()->id;
        $address = CustomerAddress::where('user_id', $userId)->first();
        $countries = Country::orderBy('name','ASC')->get();
        $user = User::where('id', $userId)->first();
        
        return view('front.account.profile', [
            'user' => $user,
            'countries' => $countries,
            'address' => $address,
        ]);
    }
    
    public function updateProfile(Request $request) {
        // echo 'Hello';
        $userId = Auth::user()->id;
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$userId.',id',
            'phone' => 'required|digits:10'
        ]);
        
        if ($validator->passes()) {
            $user = User::find($userId);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->save();
            
            session()->flash('success', 'Profile Updated Sucessfully');
            return response()->json([
                'status' => true,
                'message' => 'Profile Updated Sucessfully',
            ]);
            
        } else {
            // session()->flash('error', 'Product already removed');
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
        
    }
    
    public function updateAddress(Request $request) {
        // echo 'Hello';
        $userId = Auth::user()->id;
        $validator = Validator::make($request->all(), [
            
            'first_name' => 'required|min:3',
            'last_name' => 'required',
            'email' => 'required|email',
            'country_id' => 'required',
            'address' => 'required|min:30',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required',
            'mobile' => 'required|numeric|digits:10',
            
        ]);
        
        if ($validator->passes()) {
            // $user = User::find($userId);
            CustomerAddress::updateOrCreate(
                ['user_id' => $userId],
                [
                    'user_id' => $userId,
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'mobile' => $request->mobile,
                    'country_id' => $request->country_id,
                    'address' => $request->address,
                    'apartment' => $request->apartment,
                    'city' => $request->city,
                    'state' => $request->state,
                    'zip' => $request->zip,
                    ]
                );
                
                session()->flash('success', 'Address Updated Sucessfully');
                return response()->json([
                    'status' => true,
                    'message' => 'Address Updated Sucessfully',
                ]);
                
        }
        else {
            // session()->flash('error', 'Product already removed');
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
        
    }
    
    public function logout() {
        Auth::logout();
        return redirect()->route('account.login')->with('success', 'You are succesfully logged out!');
        
    }
    
    public function orders() {
        $data = [];
        $user = Auth::user();
        $orders = Order::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        $data['orders'] = $orders;
        return view('front.account.order', $data);
    }
        
    public function orderDetail($id) {
        // echo $id;
        $data = [];
        $user = Auth::user();
        $order = Order::where('user_id', $user->id)->where('id', $id)->first();
        // dd($order);
        // if ($order->id == null) {
            //     return view('front.account.order');
            // }
            $orderItems = OrderItem::where('order_id', $id)->get();
            $data['order'] = $order;
            $data['orderItems'] = $orderItems;
            $orderItemsCount = OrderItem::where('order_id', $id)->count();
            $data['orderItemsCount'] = $orderItemsCount;
            $product = [];
            foreach ($orderItems as $orderItem) {
                // Assuming 'product_id' is the column in the OrderItem model referencing the product
                $productId = $orderItem->product_id;

                // Fetch product details based on the product_id
                $product = Product::find($productId);

                // Add product details to an array (or perform further operations)
                // if ($product) {
                //     $product[] = $product;
                // }
            }
            $data['product'] = $product;
            // dd($data);
            return view('front.account.order-details', $data);
    }
    
    public function wishlist() {
        
        $wishlists = Wishlist::where('user_id', Auth::user()->id)->with('product')->get();
        $data ['wishlists'] = $wishlists;
        return view('front.account.wishlist', $data);
        
    }
            
    public function removeProductFromWishlist(Request $request) {
        $wishlist = Wishlist::where('user_id', Auth::user()->id)->where('product_id', $request->id)->first();
        if ($wishlist == null) {
            session()->flash('error', 'Product already removed');
            return response()->json([
                'status' => true
            ]);
        }
        else {
            Wishlist::where('user_id', Auth::user()->id)->where('product_id', $request->id)->delete();
            session()->flash('success', 'Product removed successfully');
            return response()->json([
                'status' => true
            ]);
        }
    }
    
    public function showChangePasswordForm() {
        return view('front.account.change-password');
    }

    public function changePassword(Request $request) {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required|min:5',
            'confirm_password' => 'required|same:new_password',
        ]);
        if ($validator->passes()) {
            $user = User::select('id', 'password')->where('id', Auth::user()->id)->first();
            // dd($user);

            // Check entered password matches old password or not 

            if (!Hash::check($request->old_password,$user->password)) {
                session()->flash('error', 'Your old password is incorrect, please Try again.');
                return response()->json([
                    'status' => true,
                    'message' => 'Your old password is incorrect, please Try again.',
                ]); 
            }

            User::where('id', $user->id)->update([
                'password' => Hash::make($request->new_password)
            ]);
            
            session()->flash('success', 'You have successfully changed your password.');
            return response()->json([
                'status' => true,
                'message' => 'You have successfully changed your password.',
            ]);
            
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }

    public function forgotPassword() {
        return view('front.account.forgot-password');
    }

    public function processForgotPassword(Request $request) {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);
        
        if ($validator->fails()) {

            return redirect()->route('front.forgotPassword')->withInput()->withErrors($validator); 
            
        }

        $token = Str::random(60);  

        // Delete record before creating new record with same email

        \DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        \DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => now(),
        ]);

        // Send email here 

        $user = User::where('email', $request->email)->first();

        $formData = [
            'token' => $token,
            'user' => $user,
            'mail_subject' => 'You have requested to reset your password',
        ];

        Mail::to($request->email)->send(new ResetPasswordEmail($formData));

        return redirect()->route('front.forgotPassword')->with('success', 'Please check your inbox to reset your password');

        // session()->flash('success', 'Profile Updated Sucessfully');
        // return response()->json([
        //     'status' => true,
        //     'message' => 'Profile Updated Sucessfully',
        // ]);
    }

    public function resetPassword($token) {

        $tokenExist = \DB::table('password_reset_tokens')->where('token', $token)->first();

        if ($tokenExist == null) {
           return redirect()->route('front.forgotPassword')->with('error', 'Invalid Request');
        }

        return view('front.account.reset-password', [
            'token' => $token
        ]);
    }

    public function processResetPassword(Request $request) {

        $token = $request->token;

        $tokenObj = \DB::table('password_reset_tokens')->where('token', $token)->first();

        if ($tokenObj == null) {
            return redirect()->route('front.forgotPassword')->with('error', 'Invalid Request');
        }

        $user = User::where('email', $tokenObj->email)->first();

        $validator = Validator::make($request->all(), [
            'new_password' => 'required|min:5',
            'confirm_password' => 'required|same:new_password',
        ]);
        
        if ($validator->fails()) {

            return redirect()->route('front.resetPassword', $token)->withErrors($validator); 
            
        }

        User::where('id', $user->id)->update([
            'password' => Hash::make($request->new_password)
        ]);

        \DB::table('password_reset_tokens')->where('email', $user->email)->delete();

        return redirect()->route('account.login', $token)->with('success', 'You have successfully updated your password'); 


    }
}
        