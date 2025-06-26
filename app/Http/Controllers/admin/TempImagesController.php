<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class TempImagesController extends Controller
{
    public function create(Request $request){
        $image = $request->image;
        
        if(!empty($image)){
            $ext = $image->getClientOriginalExtension();
            $newName = mt_rand(100000,999999).'-'.time().'.'.$ext;
            
            $tempImage = new TempImage();
            $tempImage->name =  $newName;
            $tempImage->save();


            $image->move(public_path().'/temp',$newName);
           

            //generate temp thumbnail
            $sPath = public_path().'/temp/'.$newName;
            $dPath = public_path().'/temp/thumb/'.$newName;
            // dd($newName);
            $manager = new ImageManager(Driver::class);
            $img = $manager->read($sPath);
            $img->cover(300, 270);
            $img->save($dPath);


            return response()->json([
                'status'=>true,
                'image_id'=> $tempImage->id,
                'image_path'=> url('/temp/thumb/'.$newName),
                'message'=> 'Image uploaded successfully'
            ]);
        }
    }
}
