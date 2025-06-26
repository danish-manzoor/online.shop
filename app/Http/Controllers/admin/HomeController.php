<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\TempImage;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Stripe\Issuing\Card;

class HomeController extends Controller
{
    public function index(){
        $admin = Auth::guard('admin')->user();
        $data['totalOrders']    = Order::where('status','!=','cancelled')->count();
        $data['totalProducts']  = Product::count();
        $data['totalCustomers'] = User::where('role',1)->count();
        $data['totalSale']      = Order::where('status','!=','cancelled')->sum('grand_total');

        //Calculate current month sale
        $currentMonth = Carbon::now()->startOfMonth()->format('Y-m-d');
        $todayDate = Carbon::now()->format('Y-m-d');
        $data['currentMonthName'] = Carbon::now()->format('F');
        $data['currentMonthSale'] = Order::where('status','!=','cancelled')
                                    ->whereDate('created_at','>=',$currentMonth)
                                    ->whereDate('created_at','<=',$todayDate)
                                    ->sum('grand_total');

        //Calculate for last month
        $lastMonthStart = Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d');
        $data['lastMonthName'] = Carbon::now()->subMonth()->startOfMonth()->format('F');
        $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d');
        $data['lastMonthSale'] = Order::where('status','!=','cancelled')
                                    ->whereDate('created_at','>=',$lastMonthStart)
                                    ->whereDate('created_at','<=',$lastMonthEnd)
                                    ->sum('grand_total');
        //calculate for last 30 days
        $last30Days = Carbon::now()->subDays(30)->format('Y-m-d');
        $data['last30DaysSale'] = Order::where('status','!=','cancelled')
                                    ->whereDate('created_at','>=',$last30Days)
                                    ->whereDate('created_at','<=',$todayDate)
                                    ->sum('grand_total');
        

        
        $yesterdayDate = Carbon::now()->subDays(1)->format('Y-m-d');
        $tempImages = TempImage::whereDate('created_at','<=',$yesterdayDate)->get();
        foreach ($tempImages as $key => $value) {
            $tempPath      = public_path('/temp/'.$value->name);
            $tempPathThumb = public_path('/temp/thumb/'.$value->name);
             // Delete temporary images
            if(File::exists($tempPath)){
                File::delete($tempPath);
            }
            // Delete temporary thumbnails
            if(File::exists($tempPathThumb)){
                File::delete($tempPathThumb);
            }

            TempImage::where('id',$value->id)->delete();
        }   
        return view('admin.dashboard',$data);
    }

    public function logout(){
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login')->with('success','You are logout successfully');
    }
}
