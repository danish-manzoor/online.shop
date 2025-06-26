<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\CustomerAddress;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(){
        return view('front.accounts.register');
    }

    public function processRegistration(Request $request){
       $validator = Validator::make($request->all(),[
        'email' => 'required|unique:users',
        'name' => 'required|min:3',
        'password'=> 'required|confirmed|min:6'
       ]);

       if($validator->passes()){

            $user = new User();
            $user->name = $request->name;
            $user->email= $request->email;
            $user->phone = $request->phone;
            $user->password = Hash::make($request->password);
            $user->save();
            session()->flash('success','Your registration successfully completed');
            return response()->json([
                'status'=> true,
                'message'=> 'Your registration successfully completed' 
            ]);
       }else{
        return response()->json([
            'status'=> false,
            'errors' => $validator->errors()
        ]);
       }
    }

    public function login(){
        return view('front.accounts.login');
    }

    public function processLogin(Request $request){
        $validator = Validator::make($request->all(),[
            'email' => 'required',
            'password' => 'required|min:6'
        ]);

        if($validator->passes()){
            if(Auth::attempt(['email'=> $request->email,'password'=> $request->password],$request->get('remember'))){
                // dd(session()->get('url.intented'));
                if(session()->has('url.intented')){
                    return redirect(session()->get('url.intented'));
                }else{
                    return redirect()->route('front.accounts.profile');
                }
                

            }else{
                return redirect()->route('front.accounts.login')->with('error','Either Email/Password incorrect');
            }
        }else{
            return redirect()->route('front.accounts.login')->withErrors($validator)->withInput($request->only('email'));
        }
    }



    public function profile(){
        $data['countries'] = Country::orderBy('name','ASC')->get();
        $data['address'] = CustomerAddress::where('user_id',Auth::user()->id)->first();
        $data['user'] = Auth::user();
        return view('front.accounts.profile',$data);
    }
    public function updateProfile(Request $request){
        $user_id = Auth::user()->id;
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email'=> 'required|email|unique:users,email,'.$user_id.',id',
            'phone' => 'required'
        ]);

        if($validator->passes()){
            $user = User::find($user_id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->save();

            session()->flash('success','Profile updated successfully');
            return response()->json([
                'status' => true,
                'message' => 'Profile updated successfully'
            ]);
        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }


    public function updateAddress(Request $request){
        $user_id = Auth::user()->id;
        $validator = Validator::make($request->all(),[
            'first_name'=> 'required|min:5',
            'last_name' => 'required',
            'emailAddress' => 'required|email',
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

        CustomerAddress::updateOrCreate(['user_id'=>$user_id],[
            'fname'=> $request->first_name,
            'lname'=> $request->last_name,
            'email'=> $request->emailAddress,
            'mobile'=> $request->mobile,
            'country_id'=> $request->country,
            'address'=> $request->address,
            'apartment'=> $request->apartment,
            'city'=> $request->city,
            'state'=> $request->state,
            'zip'=> $request->zip,
            'notes'=> $request->order_notes
        ]);

        session()->flash('success','Shipping address updated successfully');
            return response()->json([
                'status' => true,
                'message' => 'Shipping address updated successfully'
            ]);
    }
    public function logout(){
        Auth::logout();
        return redirect()->route('front.accounts.login')->with('success','You are logout successfully');
    }



    public function order(){
        $data['orders'] = Order::where('user_id',Auth::user()->id)->orderBy('created_at','ASC')->get();
        return view('front.accounts.order',$data);
    }


    public function orderDetails($orderId){
        $order = Order::where(['id'=> $orderId,'user_id'=>Auth::user()->id])->first();
        if(empty($order)){
            return redirect()->route('front.accounts.profile');
        }

        $data['orderItems'] = OrderItem::where('order_id',$orderId)->get();
        // dd($data);
        $data['order'] = $order;
        return view('front.accounts.order-details',$data);
    }



    public function wishlist(){
        $data['wishlists'] = Wishlist::select('products.id as pid','products.title','products.slug','products.price','products.compare_price','wishlists.id','wishlists.user_id')->leftJoin('products','products.id','=','wishlists.product_id')
                                        ->where('wishlists.user_id',Auth::user()->id)->get();
        // dd($data);
        return view('front.accounts.wishlist',$data);
    }


    public function removeWishlist(Request $request){

        $wishlist = Wishlist::where(['user_id'=> Auth::user()->id,'id'=> $request->id])->first();
        if(empty($wishlist)){
            session()->flash('error','Wishlist not found');
            return response()->json([
                'status' => true,
                'message' => 'Wishlist not found'
            ]);
        }


        Wishlist::where(['user_id'=> Auth::user()->id,'id'=> $request->id])->delete();
        session()->flash('success','Wishlist deleted successfully');
        return response()->json([
            'status' => true,
            'message' => 'Wishlist deleted successfully'
        ]);



    }



    public function changePassword(){
        return view('front.accounts.change-password');
    }

    public function changePasswordProceed(Request $request){
       $validator = Validator::make($request->all(),[
        'old_password' => 'required',
        'new_password' => 'required',
        'confirm_password'=> 'required|same:new_password'
       ]);

       if($validator->passes()){
        $user = User::find(Auth::user()->id);
        if(Hash::check($request->old_password,$user->password)){
            $user->password = Hash::make($request->new_password);
            $user->save();
            session()->flash('success','Passwrod has been changed');
            return response()->json([
                'status' => true,
                'errors' => "Passwrod has been changed"
            ]);
        }else{
            session()->flash('error','The old password is incorrect');
            return response()->json([
                'status' => true,
                'errors' => "The old password is incorrect"
            ]);
        }
       }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
       }
    }
    
}
