<?php

use App\Mail\OrderEmail;
use App\Models\Category;
use App\Models\Country;
use App\Models\Order;
use App\Models\Page;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Mail;

function getCategories() {
    return Category::orderBy('name', 'ASC')
                    ->orderBy('id', 'desc')
                    ->where('showHome', 'Yes')
                    ->where('status', 1)
                    ->with('sub_category')
                    ->with('media') // Include this line to load the media relationship
                    ->get();   

}

function getProductImage($productId) {
    return ProductImage::where('product_id',$productId)->first();
}

function orderEmail($orderId, $userType = "customer") {
    
    $order = Order::where('id', $orderId)->with('items')->first();

    if ($userType == "customer") {
        $subject = "Thanks for your order!";
        $email = $order->email;
    }
    else {
        $subject = "You have received an Order!";
        $email = env('ADMIN_EMAIL');
    }

    $mailData = [
        'subject' => $subject,
        'order' => $order,
        'userType' => $userType,
    ];

    Mail::to($email)->send(new OrderEmail($mailData));
    // dd($order);
}

function getCountryInfo($id) {
    return Country::where('id', $id)->first(); 
}

function staticPages(){
    $pages = Page::orderBy('name', 'asc')->get();
    return $pages;
}

?>
