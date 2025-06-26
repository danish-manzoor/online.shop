<?php

use App\Mail\OrderMail;
use App\Models\Category;
use App\Models\Order;
use App\Models\Page;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Mail;

    function get_category(){
        return  Category::orderBy('id','ASC')->where(['status'=>1,'showHome'=> 'Yes'])->get();
    }

    function getProductImage($id){
        return ProductImage::where('product_id',$id)->first();
    }

    function getSlug($pid){
        return Product::where('id',$pid)->first()->slug;
    }


    function sendOrderEmail($id,$userType=null){

        $order = Order::where('id',$id)->with('getOrderItems')->first();

        if($userType == 'customer'){
            $subject = 'Thank for your order';
            $userType = 'customer';
        }else{
            $subject = 'You have received an order';
            $userType = 'admin';

        }


        $orderData = [
            'subject'=> $subject,
            'order' => $order,
            'userType'=> $userType
        ];

        Mail::to($order->email)->send(new OrderMail($orderData));
    }



    function getPages(){
        return Page::where('status',1)->orderBy('id','ASC')->get();
    }
    
?>