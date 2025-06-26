<?php

namespace App\Http\Controllers;

use App\Mail\ContactMail;
use App\Mail\ResetPasswordMail;
use App\Models\Category;
use App\Models\Page;
use App\Models\Product;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Stripe\Stripe;
use Illuminate\Support\Str;

class FrontController extends Controller
{
    public function index(){
        $data['categories'] = Category::orderBy('id','ASC')->where(['status'=>1,'showHome'=> 'Yes'])->take(8)->get();
        $data['is_featuredProducts'] = Product::orderBy('id','ASC')->where(['status'=>1,'is_featured'=>'Yes'])->take(8)->get();
        $data['latest_products'] = Product::latest('id','DESC')->where(['status'=>1])->take(8)->get();
        return view('front.home',$data);
    }

    public function pages($slug){
        $data['page'] = Page::where('slug',$slug)->first();
        if($data['page'] == ''){
         abort(404);
        }

        return view('front.page',$data);

    }
    public function ContactUs(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email'=> 'required|email',
            'subject'=> 'required',
            'message'=> 'required'
        ]);

        if($validator->passes()){
            $mailData = [
                'name' => $request->name,
                'email'=> $request->email,
                'subject'=> $request->subject,
                'message'=> $request->message
            ];

            Mail::to('danishmanzoor226@gmail.com')->send(new ContactMail($mailData));

            session()->flash('success','Your query has been sent sucessfully to admin');
            return response()->json([
                'status'=> true,
                'errors'=> 'Your query has been sent sucessfully to admin'
            ]);
        }else{
            return response()->json([
                'status'=> false,
                'errors'=> $validator->errors()
            ]);
        }
    }


    public function forgotPassword(){
        return view('front.accounts.forgot-password');
    }

    public function forgotPasswordProcess(Request $request){
        
        $validator = Validator::make($request->all(),[
            'email' => 'required|email|exists:users,email',
        ]);

        if($validator->passes()){
            $user = User::where('email',$request->email)->first();
            $token = Str::random(60);

            DB::table('password_reset_tokens')->where('email', '=', $user->email)->delete();
            
           
            DB::table('password_reset_tokens')->insert(['email'=> $user->email,'token'=> $token]);
            $data = [
                'token' => $token,
                'user'  => $user,
                'subject'=> "Request to reset your password"
            ];
            Mail::to($user->email)->send( new ResetPasswordMail($data));
            return redirect()->route('front.forgot.password')->with('success','Please check your inbox');
        }else{
            return redirect()->route('front.forgot.password')->withErrors($validator)->withInput($request->only('email'));
        }
    }

    public function resetPassword($slug){
      $data = DB::table('password_reset_tokens')->where('token',$slug)->first();
      if($data != null){
        return view('front.accounts.reset-password',['token'=>$slug]);
      }else{
        return redirect()->route('front.forgot.password')->with('error','Invalid request');
      }
    }

    public function resetPasswordProcess(Request $request){
        $data = DB::table('password_reset_tokens')->where('token',$request->token)->first();
        if($data == null){
            return redirect()->route('front.forgot.password')->with('error','Invalid request');
        }

        $validator = Validator::make($request->all(),[
            'new_password' => 'required|min:6',
            'confirm_password' => 'required|same:new_password',
        ]);

        if($validator->fails()){
            return redirect()->route('front.reset.password',$data->token)->withErrors($validator);
        }
        $info = [
            'password' => Hash::make($request->new_password),
        ];


        DB::table('password_reset_tokens')->where('email', '=', $data->email)->delete();

        User::where('email',$data->email)->update($info);
        return redirect()->route('front.accounts.login')->with('success','Your password successfully changed');
        
    }


    public function wishlist(Request $request){
        
        if(Auth::check() == false){
            session(['url.intented'=> url()->previous()]);
            return response()->json([
                'status'=> false,
                'message'=> 'user not logged in'
            ]);
        }

        $product = Product::find($request->id);
        if($product == ''){
            return response()->json([
                'status'=> false,
                'message'=> 'Product Not found'
            ]);
        }
        
        $wishlistCheck = Wishlist::where(['user_id'=>Auth::user()->id,'product_id'=> $request->id])->first();
        if(!empty($wishlistCheck)){
            return response()->json([
                'status'=> true,
                'already'=> true,
                'message'=> 'You have already added this product to your wishlist.'
            ]);
        }

        $wishlist = new Wishlist();
        $wishlist->user_id = Auth::user()->id;
        $wishlist->product_id = $request->id;
        $wishlist->save();

        return response()->json([
            'status'=> true,
            'already'=> false,
            'message'=> 'You added this product to your wishlist successfully.'
        ]);
        
    }


    public function testing(){
        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
        $price = \Stripe\Price::create([
            'product' => 'prod_QCom6IYobKru6Q',
            'unit_amount' => 100*100,
            'currency' => 'usd',
            'recurring'=>[
                'interval'=>'year'
            ]
            
        ]);

        // $product = \Stripe\Product::create([
        //     'name' => 'Typographic starter',
        //     'description'=> '1 font & 1 domain.',
        // ]);

       echo '<pre>';print_r($price);die;
    }
    public function subscription(){
        $url = "javascript:void(0)";
        if(Auth::user()->subscription_status == 1){
            $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
            $configuration = $stripe->billingPortal->configurations->create([
                'business_profile' => [
                    'privacy_policy_url' => 'https://example.com/privacy',
                    'terms_of_service_url' => 'https://example.com/terms',
                ],
                'features' => [
                    'customer_update' => [
                        'allowed_updates' => ['email', 'address','name','shipping','phone'],
                        'enabled' => true,
                    ],
                    'payment_method_update' => [
                        'enabled' => true,
                    ],
                    'subscription_update' => [
                        'default_allowed_updates' => ['price'],
                        'enabled' => true,  // Enable the subscription update feature
                        'products' => [
                            [
                                'product' => 'prod_QCoNcNuVc4skrm',
                                'prices' => ['price_1PMOmLAPZ0qgh87RW74wIj4k']
                            ],
                            [
                                'product' => 'prod_QCogeSlRnUhTFe',
                                'prices' => ['price_1PMP3lAPZ0qgh87RKpQ6hTIL']
                            ],
                            [
                                'product' => 'prod_QColCzAJIO1dYO',
                                'prices' => ['price_1PMP7qAPZ0qgh87RWcLV4pPH']
                            ],
                            [
                                'product' => 'prod_QCom6IYobKru6Q',
                                'prices' => ['price_1PMP9MAPZ0qgh87RJfeaBFgx']
                            ],
                            // Add up to 10 products as needed
                        ],
                          // Specify the default allowed updates if needed
                        'proration_behavior' => 'none'
                    ],
                    'invoice_history' => ['enabled' => true],
                    'subscription_cancel'=> ['enabled'=>true,]
                ],
            ]);
            $session = $stripe->billingPortal->sessions->create([
                'customer' => Auth::user()->stripe_customer_id,
                'configuration'=> $configuration->id,
                'return_url' => "http://localhost:8000/subscription",
            ]);
            $url = $session->url;
        }
       
        $data['url'] = $url;
        return view('front.accounts.subscription',$data);
    }

    public function subscriptionPlan($plan){
        
        $data['plan'] = ucfirst($plan);
        return view('front.accounts.subscription-plan',$data);
    }

    public function createSubscription(Request $request){
        $intent = null;
        if($request->plan == 'Personal'){
            $amount = 29;
            $name = "Personal Plan";
        }else{
            $amount = 49;
            $name = "Ultimate Plan";
        }

        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
        
            //Step 1)- Create the customer
            try{
                $customer = \Stripe\Customer::create([
                    "payment_method" => $request->payment_method_id,
                    "name" => Auth::user()->name,
                    "email" => Auth::user()->email,
                    "invoice_settings" => [
                        "default_payment_method" => $request->payment_method_id
                    ]
                ]);
            } catch (\Stripe\Exception\ApiErrorException $e) {
                return response()->json([
                    'status' => false,
                    'message'=> $e->getMessage()
                ]);
            }
            
            //Step 2) -  Create Product & price
     
        
            try {
                $price = \Stripe\Price::create([
                    'product_data' => ['name' => $name],
                    'unit_amount' => $amount*100,
                    'currency' => 'usd',
                    'recurring'=>[
                    'interval'=>'month'
                    ]
                    
                ]);
            } catch (\Stripe\Exception\ApiErrorException $e) {
                return response()->json([
                    'status' => false,
                    'message'=> $e->getMessage()
                ]);
            }
            if (isset($request->payment_method_id)) {    
                $subscription = \Stripe\Subscription::create([
                    "customer" => $customer->id,
                    'enable_incomplete_payments' => true,
                    'description'=> 'lorem ipsum',
                    "items" => [
                        [
                            "price" => $price->id,
                        ],
                    ], 
                    "expand" => ['latest_invoice.payment_intent','plan.product']
                ]);
                $intent = $subscription->latest_invoice->payment_intent;
                // dd($intent);
                if(isset($subscription->id) && !empty($subscription->id)){
                    if(empty($intent)){
                    $intent = new stdClass();
                    $intent->id  = $subscription->latest_invoice->id;
                    $intent->status      = 'succeeded'; 
                    }
                    // inserting to database
        
                } else{
                    echo json_encode('status',false);
                    exit();
                }
            }

            if(!empty($request->payment_intent_id)){
                $intent = \Stripe\PaymentIntent::retrieve(
                    $request->payment_intent_id
                );
            }

            $this->generatePaymentResponse($intent);
        
    }


    public function generatePaymentResponse($intent){
        if ($intent->status == 'requires_source_action' || $intent->status == 'requires_action' && $intent->next_action->type == 'use_stripe_sdk') {
            # Tell the client to handle the action
            echo json_encode([
                'requires_action' => true,
                'payment_intent_client_secret' => $intent->client_secret
              ]);
        } else if ($intent->status == 'succeeded') {
            
            $user = User::find(Auth::user()->id);
            $user->stripe_customer_id = $intent->customer;
            $user->subscription_status = '1';
            $user->save();

            session()->flash('success','subscription done successfully');
            
            echo json_encode([
                "success" => true
              ]);
            exit;
            
          } else {
            # Invalid status
            http_response_code(500);
            echo json_encode(['error' => 'Invalid PaymentIntent status']);
        }
    }



    
}
