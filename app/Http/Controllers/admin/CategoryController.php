<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManager;

class CategoryController extends Controller
{
    public function index(Request $request){
        $categories = Category::latest();
        if(!empty($request->get('keyword'))){
            $categories->where('name','like','%'.$request->get('keyword').'%');
        }
        $data['categories'] = $categories->paginate(10);
        // dd($request->get('keyword'));
        return view('admin.category.list',$data);
    }


    public function create(){
        return view('admin.category.create');
    }


    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required|unique:categories'
        ]);
        if($validator->passes()){
            $category = new Category();
            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->status = $request->status;
            $category->showHome = $request->showHome;
            $category->save();


            if(!empty($request->image_id)){
                $tempImage = TempImage::find($request->image_id);
                
                $extArray = explode('.',$tempImage->name);
                
                $ext = last($extArray);
               
                $newImageName = $category->id.'.'.$ext;
                // dd($newImageName);
                $sPath = public_path().'/temp/'.$tempImage->name;
                $dPath = public_path().'/uploads/category/'.$newImageName;
                File::copy($sPath,$dPath);

                $category->image = $newImageName;
                $category->save();

                //generate image thumbnail
                $image = ImageManager::imagick()->read($sPath);
                
                $dPath = public_path().'/uploads/category/thumb/'.$newImageName;
                $image->resize(450,600);
                $image->save($dPath);
            }
            // $request->session()->flash();
            session()->flash('success','Category added successfully');

            return response()->json([
                'status'=> true,
                'errors'=> 'Category added successfully'
            ]);
        }else{
            return response()->json([
                'status'=> false,
                'errors'=> $validator->errors()
            ]);
        }
    }

    public function edit($categoryId, Request $request){
        $categories = Category::find($categoryId);
        if(empty($categories)){
            return redirect()->route('category.list')->with('error','Category not found');
        }
        return view('admin.category.edit',compact('categories'));
    }

    public function update($categoryId,Request $request){
        
        $categories = Category::find($categoryId);
        
        
        if(empty($categories)){
            session()->flash('error','Cateogry not found');
            return response()->json([
                'status'=> true,
                'NotFound'=> true,
                'message'=> "Category not found"
            ]);
        }
     
            $validator = Validator::make($request->all(),[
                'name' => 'required',
                'slug' => 'required|unique:categories,slug,'.$categories->id.''
            ]);
           
            if($validator->passes()){
                
                $categories->name = $request->name;
                $categories->slug = $request->slug;
                $categories->status = $request->status;
                $categories->showHome = $request->showHome;
                $categories->save();

                if(!empty($request->image_id)){
                    $oldImage = $categories->image;
                    $tempImage = TempImage::find($request->image_id);
                    
                    $extArray = explode('.',$tempImage->name);
                    
                    $ext = last($extArray);
                   
                    $newImageName = $categories->id.'-'.time().'.'.$ext;
                    // dd($newImageName);
                    $sPath = public_path().'/temp/'.$tempImage->name;
                    $dPath = public_path().'/uploads/category/'.$newImageName;
                    File::copy($sPath,$dPath);
    
                    $categories->image = $newImageName;
                    $categories->save();
    
                    //generate image thumbnail
                    $image = ImageManager::imagick()->read($sPath);
                    
                    $dPath = public_path().'/uploads/category/thumb/'.$newImageName;
                    $image->resize(450,600);
                    $image->save($dPath);

                    //delete the old images
                    File::delete(public_path().'/uploads/category/thumb/'.$oldImage);
                    File::delete(public_path().'/uploads/category/'.$oldImage);
                }

                session()->flash('success','Category updated successfully');

                return response()->json([
                    'status'=> true,
                    'errors'=> 'Category updated successfully'
                ]);
                
            }else{
                return response()->json([
                    'status'=> false,
                    'errors'=> $validator->errors()
                ]);
            }


    
    }


    public function destroy($categoryId,Request $request){
        
        $category = Category::find($categoryId);
        // dd($category);
        if(empty($category)){
            session()->flash('error','Category not found');
            return response()->json([
                'status'=>true,
                'error' => 'Category not found'
            ]);
        }
        
        $category->delete();

        File::delete(public_path().'/uploads/category/thumb/'.$category->image);
        File::delete(public_path().'/uploads/category/'.$category->image);
        session()->flash('success','Category deleted successfully');
        return response()->json([
            'status'=> true,
            'success'=> 'Category deleted successfully' 
        ]);
    }
}
