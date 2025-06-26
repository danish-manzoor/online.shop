<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductRating;
use App\Models\SubCategory;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Colors\Rgb\Channels\Red;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ProductController extends Controller
{
    //

    public function index(){
        $data['products'] = Product::latest('id')->with('product_images')->paginate();
        // dd($data['products']);
        return view('admin.products.list',$data);
    }
    public function getSubcategory(Request $request){
        $subcategories = SubCategory::where('category_id',$request->id)->get();
        if(!empty($subcategories)){
            return response()->json([
                'status'=> true,
                'subcategories'=> $subcategories
            ]);
        }else{
            return response()->json([
                'status'=> false,
                'subcategories'=> []
            ]);
        }
    }
    public function create(){
        $data['categories'] = Category::orderBy('name','ASC')->get();
        $data['brands']  = Brand::orderBy('name','ASC')->get();
        return view('admin.products.create',$data);
    }

    public function store(Request $request){
        $rules = [
            'title'=> 'required',
            'slug' => 'required|unique:products',
            'price'=> 'required|numeric',
            'sku' => 'required|unique:products',
            'category'=> 'required|numeric',
            'track_qty'=> 'required|in:Yes,No',
            'is_featured'=>'required|in:Yes,No'
        ];

        if(!empty($request->track_qty) && $request->track_qty == 'Yes'){
            $rules['qty'] = 'required|numeric';
        }
        $validator = Validator::make($request->all(),$rules);
        if($validator->passes()){
            $product = new Product();
            $product->title = $request->title;
            $product->slug = $request->slug;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->compare_price = $request->compare_price;
            $product->sku = $request->sku;
            $product->track_qty = $request->track_qty;
            $product->qty = $request->qty;
            $product->category_id = $request->category;
            $product->sub_category_id = $request->sub_category;
            $product->status = $request->status;
            $product->brand_id = $request->brand;
            $product->is_featured = $request->is_featured;
            $product->short_description = $request->short_description;
            $product->shipping_returns = $request->shipping_returns;
            $product->related_products = ($request->related_products?implode(',',$request->related_products):'');
            $product->save();


            if(!empty($request->imageArray)){
                foreach($request->imageArray as $temp_image_id){
                    $tempImageinfo = TempImage::find($temp_image_id);
                    $extArray = explode('.',$tempImageinfo->name);
                    $ext      = last($extArray);


                    $productImage = new ProductImage();
                    $productImage->product_id = $product->id;
                    $productImage->image = 'NULL';
                    $productImage->save();

                    $newProductImage = $product->id.'-'.$productImage->id.'-'.time().'.'.$ext;
                    $productImage->image = $newProductImage;
                    $productImage->save();


                    //Large images
                    $sPath = public_path().'/temp/'.$tempImageinfo->name;
                    $dPath = public_path().'/uploads/products/large/'.$newProductImage;
                    // dd($tempImageinfo);
                    $manager = new ImageManager(new Driver());
                    $image = $manager->read($sPath);
                    $image->scale(1400, null); 
                    $image->save($dPath);


                    //small size
                    $dPath = public_path().'/uploads/products/small/'.$newProductImage;

                    // create new image instance (800 x 600)
                    // $manager = new ImageManager(Driver::class);
                    $image = $manager->read($sPath);
                    $image->cover(300, 300); 
                    $image->save($dPath);
                }
            }
            session()->flash('success','Product added successfully');
            return response()->json([
                'status'=> true,
                'success'=> 'Product added successfully'
            ]);
        }else{
            return response()->json([
                'status'=> false,
                'errors'=> $validator->errors()
            ]);
        }
    }

    public function edit($id, Request $request){
        $product = Product::find($id);
        if(empty($product)){
            session()->flash('error','Product not Found');
            return redirect()->route('product.list');
        }
        $relatedProducts = [];
        if(!empty($product->related_products)){
            $relatedProductArr = explode(',',$product->related_products);
            $relatedProducts = Product::whereIn('id',$relatedProductArr)->get();
        }
        $data['subCategories'] = SubCategory::where('category_id',$product->category_id)->get();
        $data['products']  = $product;
        $data['categories'] = Category::orderBy('name','ASC')->get();
        $data['brands']  = Brand::orderBy('name','ASC')->get(); 
        $data['productImages'] = ProductImage::where('product_id',$product->id)->get();
        $data['relatedProducts'] = $relatedProducts;
        return view('admin.products/edit',$data);
    }


    public function update($id, Request $request){
        $product = Product::find($id);
        if(empty($product)){
            return response()->json([
                'status'=> false,
                'NotFound'=> true,
                'message'=> 'Product Not found'
            ]);
        }

        $rules = [
            'title'=> 'required',
            'slug' => 'required|unique:products,slug,'.$product->id.',id',
            'price'=> 'required|numeric',
            'sku' => 'required|unique:products,sku,'.$product->id.',id',
            'category'=> 'required|numeric',
            'track_qty'=> 'required|in:Yes,No',
            'is_featured'=>'required|in:Yes,No'
        ];

        if(!empty($request->track_qty) && $request->track_qty == 'Yes'){
            $rules['qty'] = 'required|numeric';
        }

        $validator = Validator::make($request->all(),$rules);
        if($validator->passes()){
            
            $product->title = $request->title;
            $product->slug = $request->slug;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->compare_price = $request->compare_price;
            $product->sku = $request->sku;
            $product->track_qty = $request->track_qty;
            $product->qty = $request->qty;
            $product->category_id = $request->category;
            $product->sub_category_id = $request->sub_category;
            $product->status = $request->status;
            $product->brand_id = $request->brand;
            $product->is_featured = $request->is_featured;
            $product->short_description = $request->short_description;
            $product->shipping_returns = $request->shipping_returns;
            $product->related_products = ($request->related_products?implode(',',$request->related_products):'');
            $product->save();

            session()->flash('success','Product added successfully');
            return response()->json([
                'status'=> true,
                'success'=> 'Product added successfully'
            ]);
        }else{
            return response()->json([
                'status'=> false,
                'errors'=> $validator->errors()
            ]);
        }
    }


    public function destroy($id,Request $request){
        
        $product = Product::find($id);
        
        if(empty($product)){
            session()->flash('error','Product not found');
            return response()->json([
                'status'=>true,
                'error' => 'Product not found'
            ]);
        }
        
        $productImages = ProductImage::where('product_id',$id)->get();
        // dd($productImages);
        if(!empty($productImages)){
            foreach($productImages as $productImage){
                File::delete(public_path().'/uploads/products/large/'.$productImage->image);
                File::delete(public_path().'/uploads/products/small/'.$productImage->image);
            }
            ProductImage::where('product_id',$id)->delete();
        }

        $product->delete();
        session()->flash('success','Product deleted successfully');

        return response()->json([
            'status'=> true,
            'success'=> 'Product deleted successfully' 
        ]);
    }


    public function getRelatedProducts(Request $request){
        $tempProducts = [];
        if($request->term != ''){
            $products = Product::where('title','like','%'.$request->term.'%')->get();
            
            if($products != null){
                foreach($products as $product){
                    $tempProducts[] = array('id'=> $product->id,'text'=> $product->title);
                }
            }
            // print_r($tempProducts);die;
            return response()->json([
                'tags'=> ($tempProducts),
                'status'=> true
            ]);
        }
      
    }


    public function showProductRating(Request $request){
        $rating = ProductRating::latest();
        if(!empty($request->get('keyword'))){
            $rating->where('username','like','%'.$request->get('keyword').'%');
            $rating->orWhere('email','like','%'.$request->get('keyword').'%');

        }
        $data['ratings'] = $rating->paginate(10);
        // dd($request->get('keyword'));
        return view('admin.rating.list',$data);
    }

    public function updateRatingStatus(Request $request){
        $rating = ProductRating::find($request->rid);
        if($rating == null){
            session()->flash('error','Rating not found');
            return response()->json([
                'status'=> true
            ]);
        }

        $rating->status = $request->status;
        $rating->save();
        session()->flash('success','Successfully approved the status of review');
        return response()->json([
            'status'=> true
        ]);
    }


    public function destroyRating($id, Request $request){
        $rating = ProductRating::find($id);
        if($rating == null){
            session()->flash('error','Rating not found');
            return response()->json([
                'status'=> true
            ]);
        }

        $rating->delete();
        session()->flash('success','Rating successfully deleted');
        return response()->json([
            'status'=> true
        ]);
    }

    
}
