<?php

use App\Http\Controllers\admin\AdminLoginController;
use App\Http\Controllers\admin\BrandController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\DiscountCodeController;
use App\Http\Controllers\admin\HomeController;
use App\Http\Controllers\admin\OrderController;
use App\Http\Controllers\admin\PageController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\admin\ProductImageController;
use App\Http\Controllers\admin\ProductSubCategoryController;
use App\Http\Controllers\admin\SettingController;
use App\Http\Controllers\admin\ShippingController;
use App\Http\Controllers\admin\SubCategoryController;
use App\Http\Controllers\admin\TempImagesController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\ShopController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/test', function () {
//     // return view('welcome');
//     orderEmail(22);
// });


Route::get('/', [FrontController::class, 'index'])->name('front.home');
Route::get('/cart', [CartController::class, 'cart'])->name('front.cart');
Route::get('/checkout', [CartController::class, 'checkout'])->name('front.checkout');
Route::post('/process-checkout', [CartController::class, 'processCheckout'])->name('front.processCheckout');
Route::post('/add-to-cart', [CartController::class, 'addToCart'])->name('front.addToCart');
Route::post('/update-cart', [CartController::class, 'updateCart'])->name('front.updateCart');
Route::post('/delete-item', [CartController::class, 'deleteItem'])->name('front.deleteItem.cart');
Route::get('/shop/{categorySlug?}/{subCategorySlug?}', [ShopController::class, 'index'])->name('front.shop');
Route::get('/product/{slug}', [ShopController::class, 'product'])->name('front.product');
Route::get('/thanks/{orderId}', [CartController::class, 'thankyou'])->name('front.thanks');
Route::post('/get-order-summary', [CartController::class, 'getOrderSummary'])->name('front.getOrderSummary');
Route::post('/apply-discount', [CartController::class, 'applyDiscount'])->name('front.applyDiscount');
Route::post('/remove-discount', [CartController::class, 'removeCoupon'])->name('front.removeDiscount');
Route::post('/add-to-wishlist', [FrontController::class, 'addToWishlist'])->name('front.addToWishlist');
Route::get('/page/{slug}', [FrontController::class, 'page'])->name('front.page');
Route::post('/send-contact-email', [FrontController::class, 'sendContactEmail'])->name('front.sendContactEmail');
Route::get('/forgot-password', [AuthController::class, 'forgotPassword'])->name('front.forgotPassword');
Route::post('/process-forgot-password', [AuthController::class, 'processForgotPassword'])->name('front.processForgotPassword');
Route::get('/reset-password/{token}', [AuthController::class, 'resetPassword'])->name('front.resetPassword');
Route::post('/process-reset-password', [AuthController::class, 'processResetPassword'])->name('front.processResetPassword');
Route::post('/save-rating/{productId}', [ShopController::class, 'saveRating'])->name('front.saveRating');

// applyDiscount

// User Routes

Route::group(['prefix' => 'account'], function () {

    Route::group(['middleware' => 'guest'], function (){

        Route::get('/register', [AuthController::class, 'register'])->name('account.register');
        Route::get('/login', [AuthController::class, 'login'])->name('account.login');
        Route::post('/login', [AuthController::class, 'authenticate'])->name('account.authenticate');
        Route::post('/process-register', [AuthController::class, 'processRegister'])->name('account.processRegister');
        
    });

    Route::group(['middleware' => 'auth'], function (){

        Route::get('/profile', [AuthController::class, 'profile'])->name('account.profile');
        Route::post('/update-profile', [AuthController::class, 'updateProfile'])->name('account.updateProfile');
        Route::post('/update-address', [AuthController::class, 'updateAddress'])->name('account.updateAddress');
        Route::get('/change-password', [AuthController::class, 'showChangePasswordForm'])->name('account.showChangePasswordForm');
        Route::post('/process-change-password', [AuthController::class, 'changePassword'])->name('account.changePassword');
        Route::get('/my-orders', [AuthController::class, 'orders'])->name('account.orders');
        Route::get('/my-wishlist', [AuthController::class, 'wishlist'])->name('account.wishlist');
        Route::post('/remove-product-from-wishlist', [AuthController::class, 'removeProductFromWishlist'])->name('account.removeProductFromWishlist');
        Route::get('/order-details/{orderId}', [AuthController::class, 'orderDetail'])->name('account.orderDetail');
        Route::get('/logout', [AuthController::class, 'logout'])->name('account.logout');

    });

});

// Admin Routes

Route::group(['prefix' => 'admin'], function () {

    Route::group(['middleware' => 'admin.guest'], function (){

        Route::get('/login', [AdminLoginController::class, 'index'])->name('admin.login');
        Route::post('/authenticate', [AdminLoginController::class, 'authenticate'])->name('admin.authenticate');

    });
    
    Route::group(['middleware' => 'admin.auth'], function (){

        Route::get('/dashboard', [HomeController::class, 'index'])->name('admin.dashboard');
        Route::get('/logout', [HomeController::class, 'logout'])->name('admin.logout');

        // Category Routes

        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.delete');

        // Sub Categories Routes

        Route::get('/sub-categories', [SubCategoryController::class, 'index'])->name('sub-categories.index');
        Route::get('/sub-categories/create', [SubCategoryController::class, 'create'])->name('sub-categories.create');
        Route::post('/sub-categories', [SubCategoryController::class, 'store'])->name('sub-categories.store');
        Route::get('/sub-categories/{subCategory}/edit', [SubCategoryController::class, 'edit'])->name('sub-categories.edit');
        Route::put('/sub-categories/{subCategory}', [SubCategoryController::class, 'update'])->name('sub-categories.update');
        Route::delete('/sub-categories/{subCategory}', [SubCategoryController::class, 'destroy'])->name('sub-categories.delete');

        // Brands Routes

        Route::get('/brands', [BrandController::class, 'index'])->name('brands.index');
        Route::get('/brands/create', [BrandController::class, 'create'])->name('brands.create');
        Route::post('/brands', [BrandController::class, 'store'])->name('brands.store');
        Route::get('/brands/{brand}/edit', [BrandController::class, 'edit'])->name('brands.edit');
        Route::put('/brands/{brand}', [BrandController::class, 'update'])->name('brands.update');
        Route::delete('/brands/{brand}', [BrandController::class, 'destroy'])->name('brands.delete');

        // Product Routes
        
        Route::get('/products', [ProductController::class, 'index'])->name('products.index');
        Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
        Route::get('/products-subcategories', [ProductSubCategoryController::class, 'index'])->name('products-subcategories.index');
        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.delete');
        Route::get('/get-products', [ProductController::class, 'getProducts'])->name('products.getProducts');
        Route::get('/ratings', [ProductController::class, 'productRatings'])->name('products.productRatings');
        Route::get('/change-rating-status', [ProductController::class, 'changeRatingStatus'])->name('products.changeRatingStatus');

        // Shipping Routes

        Route::get('/shipping/create', [ShippingController::class, 'create'])->name('shipping.create');
        Route::post('/shipping', [ShippingController::class, 'store'])->name('shipping.store');
        Route::get('/shipping/{shipping}/edit', [ShippingController::class, 'edit'])->name('shipping.edit');
        Route::put('/shipping/{shipping}/update', [ShippingController::class, 'update'])->name('shipping.update');
        Route::delete('/shipping/{shipping}', [ShippingController::class, 'destroy'])->name('shipping.delete');

        // Coupon Code Routes

        Route::get('/coupons', [DiscountCodeController::class, 'index'])->name('coupons.index');
        Route::get('/coupon/create', [DiscountCodeController::class, 'create'])->name('coupon.create');
        Route::post('/coupon', [DiscountCodeController::class, 'store'])->name('coupon.store');
        Route::get('/coupon/{coupon}/edit', [DiscountCodeController::class, 'edit'])->name('coupon.edit');
        Route::put('/coupon/{coupon}/update', [DiscountCodeController::class, 'update'])->name('coupon.update');
        Route::delete('/coupon/{coupon}', [DiscountCodeController::class, 'destroy'])->name('coupon.delete');

        // Order Routes

        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/order/{orderId}', [OrderController::class, 'detail'])->name('order.detail');
        Route::post('/order/change-status/{orderId}', [OrderController::class, 'changeOrderStatus'])->name('order.changeOrderStatus');
        Route::post('/order/send-email/{orderId}', [OrderController::class, 'sendInvoiceEmail'])->name('order.sendInvoiceEmail');

        // User Routes

        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.delete');
        
        // Page Routes

        Route::get('/pages', [PageController::class, 'index'])->name('pages.index');
        Route::get('/pages/create', [PageController::class, 'create'])->name('pages.create');
        Route::post('/pages', [PageController::class, 'store'])->name('pages.store');
        Route::get('/pages/{page}/edit', [PageController::class, 'edit'])->name('pages.edit');
        Route::put('/pages/{page}', [PageController::class, 'update'])->name('pages.update');
        Route::delete('/pages/{page}', [PageController::class, 'destroy'])->name('pages.delete');

        // temp-images.create

        Route::post('/upload-temp-image', [TempImagesController::class, 'create'])->name('temp-images.create');
        Route::post('/upload-product-image', [TempImagesController::class, 'upload'])->name('product-images.upload');
        Route::post('/product-images/update', [ProductImageController::class, 'update'])->name('product-images.update');
        Route::delete('/product-images', [ProductImageController::class, 'destroy'])->name('product-images.destroy');

        // Setting Routes 

        Route::get('/change-password', [SettingController::class, 'showChangePasswordForm'])->name('admin.showChangePasswordForm');
        Route::post('/process-change-password', [SettingController::class, 'changePassword'])->name('admin.changePassword');


        // Get Slug from given Title

        Route::get('/getSlug',function(Request $request){

            $slug = "";

            if (!empty($request->title)) {
               $slug = Str::slug($request->title);
            }

            return response()->json([
                'status' => true,
                'slug' => $slug
            ]);
        })->name('getSlug');

    });
    
});
