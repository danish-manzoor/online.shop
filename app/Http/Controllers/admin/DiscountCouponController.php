<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\DiscountCoupon;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DiscountCouponController extends Controller
{
    public function index(Request $request){
        $discounts = DiscountCoupon::latest();
        if(!empty($request->get('keyword'))){
            $discounts->where('code','like','%'.$request->get('keyword').'%');
        }
        $data['discounts'] = $discounts->paginate(10);
        // dd($request->get('keyword'));
        return view('admin.discount.list',$data);
    }


    public function create(){
        return view('admin.discount.create');
    }


    public function store(Request $request){
       
        $validator = Validator::make($request->all(),[
            'code' => 'required',
            'type' => 'required',
            'discount_amount' => 'required|numeric',
            'status'=> 'required'
        ]);
        if($validator->passes()){

            if(!empty($request->starts_at)){
                $now = Carbon::now();
                $starts_at = Carbon::createFromFormat('Y-m-d H:i:s',$request->starts_at);
                if($starts_at->lte($now)){
                    return response()->json([
                        'errors' => ['starts_at'=> 'The start data can not less than current data']
                    ]);
                }
            }


            if(!empty($request->expires_at)){
                $starts_at = Carbon::createFromFormat('Y-m-d H:i:s',$request->starts_at);
                $expires_at = Carbon::createFromFormat('Y-m-d H:i:s',$request->expires_at);
                if($expires_at->gt($starts_at) == false){
                    return response()->json([
                        'errors' => ['expires_at'=> 'The Expiry date must be greateer than start date']
                    ]);
                }
            }

            $discount = new DiscountCoupon();
            $discount->code = $request->code;
            $discount->name = $request->name;
            $discount->description = $request->description;
            $discount->max_uses = $request->max_uses;
            $discount->max_user_uses = $request->max_user_uses;
            $discount->type = $request->type;
            $discount->discount_amount = $request->discount_amount;
            $discount->min_amount = $request->min_amount;
            $discount->starts_at = $request->starts_at;
            $discount->expires_at = $request->expires_at;
            $discount->status = $request->status;
            $discount->save();


            // $request->session()->flash();
            session()->flash('success','Discount added successfully');

            return response()->json([
                'status'=> true,
                'errors'=> 'Discount added successfully'
            ]);
        }else{
            return response()->json([
                'status'=> false,
                'errors'=> $validator->errors()
            ]);
        }
    }

    public function edit($id, Request $request){
        $discounts = DiscountCoupon::find($id);
        if(empty($discounts)){
            return redirect()->route('discount.list')->with('error','Discountd not found');
        }
        return view('admin.discount.edit',compact('discounts'));
    }

    public function update($id,Request $request){
        
        $discount = DiscountCoupon::find($id);
        
        
        if(empty($discount)){
            session()->flash('error','Discount not found');
            return response()->json([
                'status'=> true,
                'NotFound'=> true,
                'message'=> "Discount not found"
            ]);
        }
     
            $validator = Validator::make($request->all(),[
                'code' => 'required',
                'type' => 'required',
                'discount_amount' => 'required|numeric',
                'status'=> 'required'
            ]);
           
            if($validator->passes()){
                

                if(!empty($request->expires_at)){
                    $starts_at = Carbon::createFromFormat('Y-m-d H:i:s',$request->starts_at);
                    $expires_at = Carbon::createFromFormat('Y-m-d H:i:s',$request->expires_at);
                    if($expires_at->gt($starts_at) == false){
                        return response()->json([
                            'errors' => ['expires_at'=> 'The Expiry date must be greateer than start date']
                        ]);
                    }
                }

                
                $discount->code = $request->code;
                $discount->name = $request->name;
                $discount->description = $request->description;
                $discount->max_uses = $request->max_uses;
                $discount->max_user_uses = $request->max_user_uses;
                $discount->type = $request->type;
                $discount->discount_amount = $request->discount_amount;
                $discount->min_amount = $request->min_amount;
                $discount->starts_at = $request->starts_at;
                $discount->expires_at = $request->expires_at;
                $discount->status = $request->status;
                $discount->save();

                session()->flash('success','Discount updated successfully');

                return response()->json([
                    'status'=> true,
                    'errors'=> 'Discount updated successfully'
                ]);
                
            }else{
                return response()->json([
                    'status'=> false,
                    'errors'=> $validator->errors()
                ]);
            }
    }


    public function destroy($id,Request $request){
        
        $discount = DiscountCoupon::find($id);
        // dd($category);
        if(empty($discount)){
            session()->flash('error','Discount not found');
            return response()->json([
                'status'=>true,
                'error' => 'Discount not found'
            ]);
        }
        
        $discount->delete();
        session()->flash('success','Discount deleted successfully');
        return response()->json([
            'status'=> true,
            'success'=> 'Discount deleted successfully' 
        ]);
    }
}
