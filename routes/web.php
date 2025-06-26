<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\AdminLoginController;
use App\Http\Controllers\admin\BrandController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\DiscountCouponController;
use App\Http\Controllers\admin\HomeController;
use App\Http\Controllers\admin\ImageUploadController;
use App\Http\Controllers\admin\OrderController;
use App\Http\Controllers\admin\PageController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\admin\SettingController;
use App\Http\Controllers\admin\ShippingController;
use App\Http\Controllers\admin\SubCategorController;
use App\Http\Controllers\admin\TempImagesController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\SubCategoryController;
use Illuminate\Http\Request;

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

Route::get('/test', function () {
    sendOrderEmail(3);
    // return view('email.order');
});

Route::get('/', [FrontController::class, 'index'])->name('front.home');
Route::get('/shop/{categorySlug?}/{subcategorySlug?}', [ShopController::class, 'index'])->name('front.shop');
Route::get('/product/{slug}', [ShopController::class, 'product'])->name('front.product');
Route::post('/add-to-cart', [CartController::class, 'addToCart'])->name('front.add.cart');
Route::post('/add-to-delete', [CartController::class, 'DestroyCart'])->name('front.cart.delete');

//Cart routes
Route::get('/cart', [CartController::class, 'cart'])->name('front.cart');
Route::post('/cart-update', [CartController::class, 'updateCart'])->name('front.cart.update');
Route::get('/checkout', [CartController::class, 'checkout'])->name('front.checkout');
Route::post('/checkout-process', [CartController::class, 'processCheckout'])->name('process.checkout');
Route::get('/thank-you/{orderId}', [CartController::class, 'thankyou'])->name('order.thankyou');

Route::post('/cart-summary', [CartController::class, 'getCartSummary'])->name('cart.summary');
Route::post('/apply-coupon', [CartController::class, 'applyCoupon'])->name('cart.applyCoupon');
Route::post('/remove-coupon', [CartController::class, 'removeCoupon'])->name('cart.removeCoupon');
Route::post('/add-wishlist', [FrontController::class, 'wishlist'])->name('front.wishlist');
//subscription
Route::get('/subscription', [FrontController::class, 'subscription'])->name('front.accounts.subscription');
Route::get('/subscription-plan/{plan}', [FrontController::class, 'subscriptionPlan'])->name('front.accounts.subscription.plan');
Route::post('/create-subscription', [FrontController::class, 'createSubscription'])->name('front.accounts.subscription.create');
Route::get('/testing', [FrontController::class, 'testing'])->name('front.accounts.testing');

Route::get('/page/{slug}', [FrontController::class, 'pages'])->name('front.accounts.pages');
Route::post('/contage-page', [FrontController::class, 'ContactUs'])->name('front.accounts.contact');

//Forgot password
Route::get('/forgot-password', [FrontController::class, 'forgotPassword'])->name('front.forgot.password');
Route::post('/forgot-password', [FrontController::class, 'forgotPasswordProcess'])->name('front.accounts.forgotpassword');
Route::get('/reset-password/{token}', [FrontController::class, 'resetPassword'])->name('front.reset.password');
Route::post('/reset-password', [FrontController::class, 'resetPasswordProcess'])->name('front.resetpasswordprocess');

Route::post('/add-review/{product_id}', [ShopController::class, 'addRating'])->name('front.rating');






Route::group(['prefix' => 'account'], function () {


    Route::group(['middleware' => 'guest'], function () {
        Route::get('/register', [AuthController::class, 'register'])->name('front.accounts.register');
        Route::post('/register', [AuthController::class, 'processRegistration'])->name('front.accounts.processRegistration');
        
        Route::get('/login', [AuthController::class, 'login'])->name('front.accounts.login');
        Route::post('/login', [AuthController::class, 'processLogin'])->name('front.accounts.processLogin');

    });



    Route::group(['middleware' => 'auth'], function () {
        Route::get('/profile', [AuthController::class, 'profile'])->name('front.accounts.profile');
        Route::post('/profile', [AuthController::class, 'updateProfile'])->name('front.accounts.updateProfile');
        Route::post('/update-address', [AuthController::class, 'updateAddress'])->name('front.accounts.updateAddress');
        
        Route::get('/logout', [AuthController::class, 'logout'])->name('front.accounts.logout');

        Route::get('/orders', [AuthController::class, 'Order'])->name('front.accounts.order');
        Route::get('/order-details/{orderID}', [AuthController::class, 'orderDetails'])->name('front.accounts.orderDetails');
        Route::get('/wishlist', [AuthController::class, 'wishlist'])->name('front.accounts.wishlist');
        Route::post('/remove-wishlist', [AuthController::class, 'removeWishlist'])->name('front.accounts.removeWishlist');
        
        Route::get('/change-password', [AuthController::class, 'changePassword'])->name('change.password');
        Route::post('/change-password', [AuthController::class, 'changePasswordProceed'])->name('change.password.process');

    });
});




// Admin routes
Route::group(['prefix' => 'admin'], function () {

    Route::group(['middleware' => 'admin.guest'], function () {
        Route::get('/login', [AdminLoginController::class, 'index'])->name('admin.login');
        Route::post('/authenticate', [AdminLoginController::class, 'authenticate'])->name('admin.authenticate');
    });

    Route::group(['middleware' => 'admin.auth'], function () {
        Route::get('/dashobard', [HomeController::class, 'index'])->name('admin.dashobard');
        Route::get('/logout', [HomeController::class, 'logout'])->name('admin.logout');


        //Category Routes
        Route::get('/category', [CategoryController::class, 'index'])->name('category.list');
        Route::get('/category/create', [CategoryController::class, 'create'])->name('category.create');
        Route::post('/category', [CategoryController::class, 'store'])->name('category.store');
        Route::get('/category/{category}/edit', [CategoryController::class, 'edit'])->name('category.edit');
        Route::put('/category/{category}', [CategoryController::class, 'update'])->name('category.update');
        Route::delete('/category/{category}', [CategoryController::class, 'destroy'])->name('category.delete');
        
        
        //Users Routes
        Route::get('/users', [UserController::class, 'index'])->name('users.list');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{users}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{users}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{users}', [UserController::class, 'destroy'])->name('users.delete');
        
        //Page Routes
        Route::get('/pages', [PageController::class, 'index'])->name('pages.list');
        Route::get('/pages/create', [PageController::class, 'create'])->name('pages.create');
        Route::post('/pages', [PageController::class, 'store'])->name('pages.store');
        Route::get('/pages/{pages}/edit', [PageController::class, 'edit'])->name('pages.edit');
        Route::put('/pages/{pages}', [PageController::class, 'update'])->name('pages.update');
        Route::delete('/pages/{pages}', [PageController::class, 'destroy'])->name('pages.delete');
        
        //User reviews Routes
        Route::get('/rating', [ProductController::class, 'showProductRating'])->name('rating.list');
        Route::post('/update-status-rating', [ProductController::class, 'updateRatingStatus'])->name('rating.update.status');
        Route::delete('/rating/{id}', [ProductController::class, 'destroyRating'])->name('rating.delete');


        //Settings Routes
        Route::get('/change-password', [SettingController::class, 'showChangePasswordForm'])->name('settings.passwrod.change');
        Route::post('/change-password', [SettingController::class, 'changePassword'])->name('settings.changePassword');


        // temp-images.create
        Route::post('/upload-temp-images', [TempImagesController::class, 'create'])->name('temp-images.create');


        // Sub-categories routes
        Route::get('/sub-category', [SubCategorController::class, 'index'])->name('sub-category.list');
        Route::get('/sub-category/create', [SubCategorController::class, 'create'])->name('sub-category.create');
        Route::post('/sub-category', [SubCategorController::class, 'store'])->name('sub-category.store');
        Route::get('/sub-category/{subcategory}/edit', [SubCategorController::class, 'edit'])->name('sub-category.edit');
        Route::put('/sub-category/{subcategory}', [SubCategorController::class, 'update'])->name('sub-category.update');
        Route::delete('/sub-category/{category}', [SubCategorController::class, 'destroy'])->name('sub-category.delete');
        

        //Brands Routes
        Route::get('/brand', [BrandController::class, 'index'])->name('brand.list');
        Route::get('/brand/create', [BrandController::class, 'create'])->name('brand.create');
        Route::post('/brand', [BrandController::class, 'store'])->name('brand.store');
        Route::get('/brand/{category}/edit', [BrandController::class, 'edit'])->name('brand.edit');
        Route::put('/brand/{category}', [BrandController::class, 'update'])->name('brand.update');
        Route::delete('/brand/{category}', [BrandController::class, 'destroy'])->name('brand.delete');
        

        // Products Routes

        Route::get('/products', [ProductController::class, 'index'])->name('products.list');
        Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
        Route::get('/products/{product_id}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product_id}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.delete');
        //Related products route
        Route::get('/related-products', [ProductController::class, 'getRelatedProducts'])->name('products.related');


        //Shipping Routes
        Route::get('/shipping', [ShippingController::class, 'create'])->name('shipping.create');
        Route::post('/shipping', [ShippingController::class, 'store'])->name('shipping.store');
        Route::get('/shipping/{shipping}/edit', [ShippingController::class, 'edit'])->name('shipping.edit');
        Route::put('/shipping/{shipping}', [ShippingController::class, 'update'])->name('shipping.update');
        Route::delete('/shipping/{shipping}', [ShippingController::class, 'destroy'])->name('shipping.delete');
        

        //Discount code Routes
        Route::get('/discount', [DiscountCouponController::class, 'index'])->name('discount.list');
        Route::get('/discount/create', [DiscountCouponController::class, 'create'])->name('discount.create');
        Route::post('/discount', [DiscountCouponController::class, 'store'])->name('discount.store');
        Route::get('/discount/{category}/edit', [DiscountCouponController::class, 'edit'])->name('discount.edit');
        Route::put('/discount/{category}', [DiscountCouponController::class, 'update'])->name('discount.update');
        Route::delete('/discount/{category}', [DiscountCouponController::class, 'destroy'])->name('discount.delete');
        

        //Order Routes
        Route::get('/order', [OrderController::class, 'index'])->name('order.list');
        Route::get('/order-details/{orderId}', [OrderController::class, 'orderDetails'])->name('order.details');
        Route::post('/update-order/{orderId}', [OrderController::class, 'update'])->name('order.update');
        Route::post('/order-invoice/{orderID}', [OrderController::class, 'orderInvoice'])->name('order.orderInvoice');


        //Route for upload images for product edit
        Route::post('/products-image/upload', [ImageUploadController::class, 'uploadImage'])->name('products.upload.image');
        Route::post('/products-image/destroy', [ImageUploadController::class, 'destroy'])->name('products.image.destroy');

        //Get the Subcategores 
        Route::get('/get/subcategory', [ProductController::class, 'getSubcategory'])->name('get.subcategory');

        Route::get('/getSlug', function (Request $request) {
            $slug = '';
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
