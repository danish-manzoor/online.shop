<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;


class ImageUploadController extends Controller
{
    public function uploadImage(Request $request){
        // dd($request->toArray());
        $image = $request->image;
        if(!empty($image)){
            $ext = $image->getClientOriginalExtension();
            $sPath = $image->getPathName();
            
            $productImage = new ProductImage();
            $productImage->product_id = $request->product_id;
            $productImage->image = 'NULL';
            $productImage->save();

            $newProductImage = $request->product_id.'-'.$productImage->id.'-'.time().'.'.$ext;
            $productImage->image = $newProductImage;
            $productImage->save();

            //Large images

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


            return response()->json([
                'status'=>true,
                'image_id'=> $productImage->id,
                'image_path'=> url('/uploads/products/small/'.$productImage->image),
                'message'=> 'Image uploaded successfully'
            ]);

        }
    }


    public function destroy(Request $request){

        $productImageId = $request->id;
        $productImage = ProductImage::find($productImageId);
        if(!empty($productImage)){
            File::delete(public_path('/uploads/products/large/'.$productImage->image));
            File::delete(public_path('/uploads/products/small/'.$productImage->image));
            $productImage->delete();

            return response()->json([
                'status'=>true,
                'message'=> 'Image Delete successfully'
            ]);
        }else{
            return response()->json([
                'status'=>true,
                'message'=> 'Image Not found'
            ]);
        }
        
    }
}
