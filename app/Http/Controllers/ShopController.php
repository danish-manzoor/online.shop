<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductRating;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShopController extends Controller
{
    public function index(Request $request, $categorySlug=null,$subcategorySlug=null){
        $categorySelected = '';
        $subcategorySelected = '';
        $selectedBrands = [];
        $min_price = '';
        $max_price = '';
        $data['categories'] = Category::latest('id','ASC')->where('status',1)->get();
        $data['brands'] = Brand::latest('id','ASC')->where('status',1)->get();


        $products = Product::where('status',1);
        
        //Apply filters
        if(!empty($categorySlug)){
            
            $category = Category::where('slug',$categorySlug)->first();
            // dd($category);
            $products = $products->where('category_id',$category->id);
            $categorySelected = $category->id;
        }

        if(!empty($subcategorySlug)){
            $subcategory = SubCategory::where('slug',$subcategorySlug)->first();
            $products = $products->where('sub_category_id',$subcategory->id);
            $subcategorySelected = $subcategory->id;
        }
        // Apply search filter
        if(!empty($request->get('search'))){
            $products = $products->where('title','like','%'.$request->get('search').'%');
            $products = $products->Where('slug','like','%'.$request->get('search').'%');
        }
        if(!empty($request->get('brands'))){
            $selectedBrands = explode(',',$request->get('brands'));
            $products = $products->whereIn('brand_id',$selectedBrands);
        }

        if($request->get('price_min') != '' && $request->get('price_max') != ''){
            $min_price = $request->get('price_min');
            $max_price = $request->get('price_max');
            if($max_price == 1000){
                $products = $products->whereBetween('price',[intval($min_price),1000000]);
            }else{
                
                $products = $products->whereBetween('price',[intval($min_price),intval($max_price)]);
            }
        }
        

        if($request->get('sort') != ''){
            if($request->get('sort') == 'latest'){
                $products = $products->orderBy('id','DESC');
            }elseif($request->get('sort') == 'price_asc'){
                $products = $products->orderBy('price','ASC');
            }else{
                $products = $products->orderBy('price','DESC');
            }
        }else{
            $products = $products->orderBy('id','DESC');
        }
        $data['products'] = $products->paginate(6);
        $data['subcategorySelected'] = $subcategorySelected;
        $data['categorySelected'] = $categorySelected;
        $data['selectedBrands'] = $selectedBrands;
        $data['min_price'] = intval($min_price);
        $data['max_price'] = (intval($max_price) == 0? 1000:intval($max_price));
        $data['sort'] = $request->get('sort');

        // dd($data);
        

        return view('front.shop',$data);
    }


    public function product($slug){
        $product = Product::where('slug',$slug)
                        ->withCount('product_rating')
                        ->withSum('product_rating','rating')  
                        ->with('product_rating')  
                        ->first();
        // dd($product);
        if(empty($product)){
            abort(404);
        }
        $relatedProduct = [];
        if(!empty($product->related_products)){
            $relatedProductArr = explode(',',$product->related_products);
            $relatedProduct = Product::whereIn('id',$relatedProductArr)->get();
        }

        $avgRating = '0.00';
        $perRating = 0;
        if($product->product_rating_count > 0){
            $avgRating = ($product->product_rating_sum_rating/$product->product_rating_count);
            $perRating = ($avgRating*100)/5;
            
        }
        $data['perRating'] = $perRating;
        $data['avgRating'] = $avgRating;
        $data['product'] = $product;
        $data['relatedProducts'] = $relatedProduct;
        // dd($data);
        return view('front.product',$data);   
    }


    public function addRating($id, Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required|min:5',
            'email'=> 'required|email',
            'comment'=> 'required|min:10',
            'rating'=> 'required',
        ]);


        if($validator->passes()){
            $productRating = new ProductRating();
            $productRating->product_id = $id;
            $productRating->username = $request->name;
            $productRating->email = $request->email;
            $productRating->comment = $request->comment;
            $productRating->rating = $request->rating;
            $productRating->status = 0;
            $productRating->save();
            session()->flash('success',"Thanks for your review");
            return response()->json([
                'status'=> true,
                'errors' => "Thanks for your review"
            ]);

        }else{
            return response()->json([
                'status'=> false,
                'errors' => $validator->errors()
            ]);
        }
    }
}
