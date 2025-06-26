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
    public function addToCart(Request $request){
        $product = Product::find($request->id);
        if(empty($product)){
            return response()->json([
                'status'=>false,
                'message'=> 'Product not Found'
            ]);
        }
       
        if(Cart::count() > 0){
            $cart = Cart::content();
            $productAlreadyexit = false;
            foreach($cart as $item){
                if($item->id == $product->id){
                   $productAlreadyexit = true;
                    
                }
            }
            if($productAlreadyexit){
                    session()->flash('success',$product->title.' already in the cart');
                    return response()->json([
                        'status'=> false,
                        'message'=> $product->title.' already in the cart'
                    ]);
            }else{
                    $productImage = (!empty($product->product_images)?$product->product_images->first():'');
                    Cart::add($product->id, $product->title, 1, $product->price, ['productImage' => $productImage],0);
                    session()->flash('success',$product->title.' added in the cart');
                    return response()->json([
                        'status'=>true,
                        'message'=> $product->title.' added in the cart'
                    ]);
            }
            
        }else{
            $productImage = (!empty($product->product_images)?$product->product_images->first():'');
            Cart::add($product->id, $product->title, 1, $product->price, ['productImage' => $productImage],0);
            session()->flash('success',$product->title.' added in the cart');
            return response()->json([
                'status'=>true,
                'message'=> $product->title.' added in the cart'
            ]);
        }
    }

    public function cart(){

        // dd(Cart::content());
        return view('front.cart');
    }


    public function updateCart(Request $request){
        // dd($request);
        if(!empty($request->id) && !empty($request->qty)){
            $cartInfo = Cart::get($request->id);
            $product  = Product::find($cartInfo->id);
            if($product->track_qty == 'Yes' && $request->qty > $product->qty){
                session()->flash('error',"Sorry, you cannot purchase more than the available quantity. Available quantity: " . $product->qty);
                return response()->json([
                    'status'=>false,
                    'message'=> "Sorry, you cannot purchase more than the available quantity. Available quantity: " . $product->qty
                ]);
            }else{
                
                Cart::update($request->id, $request->qty);
                session()->flash('success','Cart updated successfully');
                return response()->json([
                    'status'=>true,
                    'message'=> $product->title.' updated in the cart'
                ]);
            }
        }

    }

    public function DestroyCart(Request $request){
        $cartInfo = Cart::get($request->id);
        if($cartInfo == null){
            session()->flash('error','Cart not found');
            return response()->json([
                'status'=>false,
                'message'=> "Cart not found"
            ]);
        }


        Cart::remove($request->id);
        session()->flash('success','Cart successfully removed');
        return response()->json([
            'status'=>true,
            'message'=> "Cart successfully removed"
        ]);
    }


    public function checkout(){

        //check if the cart is empty or not
        if(Cart::count() == 0){
            return redirect()->route('front.cart')->with('error','Please add atleast one product into the cart to access the checkout pge');
        }

        if(Auth::check() == false){
            if(!session()->has('url.intented')){
                $url = url()->current();
                session(['url.intented'=> $url]);
            }
            
            return redirect()->route('front.accounts.login');
        }
        session()->forget('url.intented');


        $user_id = Auth::user()->id;
        $CustomerAddress = CustomerAddress::where('user_id',$user_id)->first();
        $totalQty = 0;
        $subtotal = Cart::subtotal(2,'.','');
        $grandTotal = 0;
        $discountAmount = 0;
        $shippingCost = 0;
        if(session()->has('code')){
            $code = session()->get('code');
            if($code->type == 'fixed'){
                $discountAmount = $code->discount_amount;
            }else{
                $discountAmount = ($subtotal/100* $code->discount_amount);
            }
        }
        
        if($CustomerAddress != null){
            $shippingInfo = ShippingCharge::where('country_id',$CustomerAddress->country_id)->first();
            if($shippingInfo != null){
                $shippingCost = $shippingInfo->amount;
            }else{
                $shippingInfo = ShippingCharge::where('country_id','rest_of_world')->first();
                $shippingCost = $shippingInfo->amount;
            }
            $data['customerAddress'] = $CustomerAddress;
        }else{
            $data['customerAddress'] = '';
        }
        //calculate the total products quantity

        foreach (Cart::content() as $value) {
           $totalQty += $value->qty;
        }
        // var_dump($subtotal);
        $data['discount'] = $discountAmount;
        $data['subtotal'] = $subtotal;
        $data['shippingCost'] = $shippingCost*$totalQty;
        $data['grandTotal'] = (($subtotal-$discountAmount)+$data['shippingCost']);
        $data['countries'] = Country::orderBy('id','ASC')->get();

        return view('front.checkout',$data);
    }

    public function processCheckout(Request $request){

        $validator = Validator::make($request->all(),[
            'first_name'=> 'required|min:5',
            'last_name' => 'required',
            'email'     => 'required|email',
            'mobile'    => 'required',
            'country'   => 'required',
            'address'   => 'required',
            'city'      => 'required',
            'state'     => 'required',
            'zip'       => 'required',
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

        // Step 1 - Save the customer addresses
        $user_id = Auth::user()->id;
        CustomerAddress::updateOrCreate(['user_id'=>$user_id],[
            'fname'=> $request->first_name,
            'lname'=> $request->last_name,
            'email'=> $request->email,
            'mobile'=> $request->mobile,
            'country_id'=> $request->country,
            'address'=> $request->address,
            'apartment'=> $request->apartment,
            'city'=> $request->city,
            'state'=> $request->state,
            'zip'=> $request->zip,
            'notes'=> $request->order_notes
        ]);

        // Step 2 - Save the order information


        //Calculation

        $subtotal = Cart::subtotal(2,'.','');
        $shipping = 0;
        $totalQty = 0;
        $discount = 0;
        $couponCode = '';
        $coupon_code_id = null;

        // Apply discount code
        if(session()->has('code')){
            $code = session()->get('code');
            if($code->type == 'fixed'){
                $discount = $code->discount_amount;
            }else{
                $discount = ($subtotal/100* $code->discount_amount);
            }

            $couponCode = $code->code;
            $coupon_code_id = $code->id;
        }


        if(!empty($request->country)){
            $shippingInfo = ShippingCharge::where('country_id',$request->country)->first();
            if($shippingInfo != null){
                $shippingCost = $shippingInfo->amount;
            }else{
                $shippingInfo = ShippingCharge::where('country_id','rest_of_world')->first();
                $shippingCost = $shippingInfo->amount;
            }
            // calculate the qty
            foreach (Cart::content() as $value) {
                $totalQty += $value->qty;
            }

            $shipping = $totalQty*$shippingCost;
            $grandTotal = ($subtotal-$discount)+$shipping;
        }

        $order = new Order();
        $order->user_id = $user_id;
        $order->subtotal = $subtotal;
        $order->grand_total = $grandTotal;
        $order->shipping = $shipping;
        $order->subtotal = $subtotal;
        $order->fname = $subtotal;
        $order->discount = $discount;
        $order->coupon_code = $couponCode;
        $order->coupon_code_id = $coupon_code_id;
        $order->fname = $request->first_name;
        $order->lname = $request->last_name;
        $order->email = $request->email;
        $order->mobile = $request->mobile;
        $order->country_id = $request->country;
        $order->address = $request->address;
        $order->apartment = $request->apartment;
        $order->city = $request->city;
        $order->state = $request->state;
        $order->zip = $request->zip;
        $order->notes = $request->notes;
        $order->status = 'pending';
        $order->payment_status = 'not paid';
        $order->save();
        

         // Step 3 - Save the order items information
        foreach (Cart::content() as $items) {
           $orderItems = new OrderItem();
           $orderItems->order_id = $order->id;
           $orderItems->product_id = $items->id;
           $orderItems->name = $items->name;
           $orderItems->price = $items->price;
           $orderItems->qty = $items->qty;
           $orderItems->total = $items->price* $items->qty;
           $orderItems->save();

           //update the Qty
           $productInformation = Product::find($items->id);
        //    dd($productInformation);
           if($productInformation->track_qty == 'Yes'){
              $remaingQty = $productInformation->qty - $items->qty;
              
              $productInformation->qty = $remaingQty;
              $productInformation->save();
           }
        }

        sendOrderEmail($order->id,'customer');
        Cart::destroy();
        session()->forget('code');
         // Step 4 - return the response
        session()->flash('success','Order successfully completed');
         return response()->json([
            'status'=> true,
            'orderID'=> $order->id,
            'message'=> 'Order successfully completed'
         ]);
      
    }


    public function thankyou($id){
        $data['orderID'] = $id;
        return view('front.thankyou',$data);
    }

    public function applyCoupon(Request $request){
        if(!empty($request->code) && !empty($request->id)){
            $discountCode = DiscountCoupon::where('code',$request->code)->first();

            if(empty($discountCode)){
                return response()->json([
                    'status'=> false,
                    'message'=> 'Invalid Coupon code,Please type correct code'
                ]);
            }


            $now = Carbon::now();

            
            if(!empty($discountCode->starts_at)){
                $startsAt = Carbon::createFromFormat('Y-m-d H:i:s', $discountCode->starts_at);
                
                if($now->lt($startsAt)){
                    return response()->json([
                        'status'=> false,
                        'message'=> 'Invalid coupon code, Please type correct code'
                     ]);
                }
            }


            if(!empty($discountCode->expires_at)){
                $expiresAt = Carbon::createFromFormat('Y-m-d H:i:s', $discountCode->expires_at);
                if($now->gt($expiresAt)){
                    return response()->json([
                        'status'=> false,
                        'message'=> 'Coupon code has been expired'
                     ]);
                }
            }


            session()->put('code',$discountCode);

            return $this->getCartSummary($request);

        }else{
            return response()->json([
                'status'=> false,
                'message'=> 'Please fill the coupon field'
             ]);
        }
    }


    public function removeCoupon(Request $request){
        session()->forget('code');
        
        return $this->getCartSummary($request);
    }
    public function getCartSummary(Request $request){
        $totalQty = 0;
        $shippingCost = 0;
        $grandTotal = 0;
        $subtotal = Cart::subtotal(2,'.','');
        $discountAmount = 0;
        $message = '';
        //Apply coupon code

        if(session()->has('code')){
            $code = session()->get('code');
            if($code->type == 'fixed'){
                $discountAmount = $code->discount_amount;
            }else{
                $discountAmount = ($subtotal/100* $code->discount_amount);
            }

            $message = '<div class="input-group apply-coupan mt-4" id="coupon-row">
                    <span id="couponText">'.$code->code.'</span>
                    <button type="button" class="btn btn-danger btn-sm ms-3" id="removeCoupon">Remove</button>
                </div>';
        }

        //Apply country 
        if(!empty($request->id)){
            $shippingInfo = ShippingCharge::where('country_id',$request->id)->first();
            if($shippingInfo != null){
                $shippingCost = $shippingInfo->amount;
            }else{
                $shippingInfo = ShippingCharge::where('country_id','rest_of_world')->first();
                $shippingCost = $shippingInfo->amount;
            }
            // calculate the qty
            foreach (Cart::content() as $value) {
                $totalQty += $value->qty;
            }

            $shippingCost = $totalQty*$shippingCost;
            $grandTotal = ($subtotal-$discountAmount)+$shippingCost;
        }else{
            $shippingCost = 0;
            $grandTotal = ($subtotal-$discountAmount)+$shippingCost;
        }
        

        return response()->json([
            'status'=> true,
            'discount'=> number_format($discountAmount,2),
            'total' => number_format($grandTotal,2),
            'shipping'=> number_format($shippingCost,2),
            'coponString'=> $message,
        ]);
    }
}
