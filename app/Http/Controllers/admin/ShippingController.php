<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\ShippingCharge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShippingController extends Controller
{
    
    public function create(){
        // $shipping = ShippingCharge::leftJoin('countries', 'shipping_charges.country_id', '=', 'countries.id');
        $shipping = ShippingCharge::leftJoin('countries', 'shipping_charges.country_id', '=', 'countries.id')
                          ->select('shipping_charges.*', 'countries.name as country_name')
                          ->get();
        // dd($shipping);
       $data['shipping'] = $shipping;
        $data['countries'] = Country::orderBy('id','ASC')->get();
        return view('admin.shipping.create',$data);
    }


    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'country' => 'required',
            'amount' => 'required|numeric'
        ]);
        if($validator->passes()){
            //check if there is already added shipping
            $checkShipping = ShippingCharge::where('country_id',$request->country)->first();
            if($checkShipping != null){
                session()->flash('error','Shipping already exist For this country');

                return response()->json([
                    'status'=> true,
                    'errors'=> 'Shipping already exist For this country'
                ]);
            }
            $shipping = new ShippingCharge();
            $shipping->country_id = $request->country;
            $shipping->amount = $request->amount;
            $shipping->save();
            session()->flash('success','Shipping charges added successfully');

            return response()->json([
                'status'=> true,
                'errors'=> 'Shipping charges added successfully'
            ]);
        }else{
            return response()->json([
                'status'=> false,
                'errors'=> $validator->errors()
            ]);
        }
    }

    public function edit($Id, Request $request){
        $shipping = ShippingCharge::find($Id);
        if(empty($shipping)){
            return redirect()->route('shipping.create')->with('error','Shiping charge not found');
        }
        $data['countries'] = Country::orderBy('id','ASC')->get();
        $data['shipping'] = $shipping;
        return view('admin.shipping.edit',$data);
    }

    public function update($Id,Request $request){
        
        $shipping = ShippingCharge::find($Id);
        
        if(empty($shipping)){
            session()->flash('error','Shipping not found');
            return response()->json([
                'status'=> true,
                'NotFound'=> true,
                'message'=> "Shipping not found"
            ]);
        }
     
            $validator = Validator::make($request->all(),[
                'country' => 'required',
                'amount' => 'required|numeric'
            ]);
           
            if($validator->passes()){

                $checkShipping = ShippingCharge::where('country_id', $request->country)
                                                ->where('id', '!=', $Id)
                                                ->first();
                if($checkShipping != null){
                    session()->flash('error','Shipping already exist For this country');

                    return response()->json([
                        'status'=> true,
                        'errors'=> 'Shipping already exist For this country'
                    ]);
                }
                $shipping->country_id = $request->country;
                $shipping->amount = $request->amount;
                $shipping->save();
                session()->flash('success','Shipping charges updated successfully');

                return response()->json([
                    'status'=> true,
                    'errors'=> 'Shipping charges updated successfully'
                ]);
                    
                }else{
                    return response()->json([
                        'status'=> false,
                        'errors'=> $validator->errors()
                    ]);
                }


    
    }


    public function destroy($categoryId,Request $request){
        
        $shipping = ShippingCharge::find($categoryId);
        // dd($category);
        if(empty($shipping)){
            session()->flash('error','Shipping not found');
            return response()->json([
                'status'=>true,
                'error' => 'Shipping not found'
            ]);
        }
        
        $shipping->delete();

        session()->flash('success','Shipping deleted successfully');
        return response()->json([
            'status'=> true,
            'success'=> 'Shipping deleted successfully' 
        ]);
    }
}
