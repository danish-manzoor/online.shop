<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request){
        $orders = Order::select('orders.*','users.name','users.email')
                        ->leftJoin('users','orders.user_id','=','users.id');
        if(!empty($request->get('keyword'))){
            $orders->where('users.name','like','%'.$request->get('keyword').'%');
            $orders->orWhere('users.email','like','%'.$request->get('keyword').'%');
            $orders->orWhere('orders.id','like','%'.$request->get('keyword').'%');
        }
        $data['orders'] = $orders->paginate(10);
        // dd($data);
        return view('admin.order.list',$data);
    }


    public function orderDetails($id){
        // die('dfa');
        $orders = Order::select('orders.*','countries.name as countryName')
                        ->leftJoin('countries','countries.id','=','orders.country_id')
                        ->where('orders.id',$id)->first();
        // dd($orders);
        if(empty($orders)){
            return redirect()->route('order.list');
        }

        $data['order'] = $orders;
        $data['orderItems'] = OrderItem::where('order_id',$id)->get();
        return view('admin.order.order-details',$data);
    }


    public function update(Request $request,$id){
        if(empty($request->status)){
            return response()->json([
                'status'=> false,
                'message'=> "Order status required"
            ]);
        }

        $order = Order::find($id);
        $order->status = $request->status;
        $order->shipped_date = $request->shipped_date;
        $order->save();

        session()->flash('success','Order status updated successfully');
        return response()->json([
            'status'=> true,
            'message'=> 'Order status updated successfully'
        ]);
    }

    
    public function orderInvoice(Request $request, $id){
        
        sendOrderEmail($id,$request->userType);

        return response()->json([
            'status'=> true,
            'message'=> 'Invoice send successfully'
        ]);
    }
}
