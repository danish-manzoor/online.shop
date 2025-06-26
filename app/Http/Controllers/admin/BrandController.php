<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BrandController extends Controller
{
    public function index(Request $request){
        $brands = Brand::latest();
        if(!empty($request->get('keyword'))){
            $brands->where('name','like','%'.$request->get('keyword').'%');
        }
        $data['brands'] = $brands->paginate(10);
        // dd($request->get('keyword'));
        return view('admin.brands.list',$data);
    }


    public function create(){
        return view('admin.brands.create');
    }


    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required|unique:brands'
        ]);
        if($validator->passes()){
            $brand = new Brand();
            $brand->name = $request->name;
            $brand->slug = $request->slug;
            $brand->status = $request->status;
            $brand->save();


            // $request->session()->flash();
            $request->session()->flash('success','Brand added successfully');

            return response()->json([
                'status'=> true,
                'errors'=> 'Brand added successfully'
            ]);
        }else{
            return response()->json([
                'status'=> false,
                'errors'=> $validator->errors()
            ]);
        }
    }

    public function edit($id, Request $request){
        $brands = Brand::find($id);
        if(empty($brands)){
            return redirect()->route('brand.list')->with('error','Brand not found');
        }
        return view('admin.brands.edit',compact('brands'));
    }

    public function update($id,Request $request){
        
        $brand = Brand::find($id);
        
        
        if(empty($brand)){
            $request->session()->flash('error','Brand not found');
            return response()->json([
                'status'=> true,
                'NotFound'=> true,
                'message'=> "Brand not found"
            ]);
        }
     
            $validator = Validator::make($request->all(),[
                'name' => 'required',
                'slug' => 'required|unique:brands,slug,'.$brand->id.',id'
            ]);
           
            if($validator->passes()){
                
                $brand->name = $request->name;
                $brand->slug = $request->slug;
                $brand->status = $request->status;
                $brand->save();

                $request->session()->flash('success','Brand updated successfully');

                return response()->json([
                    'status'=> true,
                    'errors'=> 'Brand updated successfully'
                ]);
                
            }else{
                return response()->json([
                    'status'=> false,
                    'errors'=> $validator->errors()
                ]);
            }
    }


    public function destroy($id,Request $request){
        
        $brand = Brand::find($id);
        // dd($category);
        if(empty($brand)){
            $request->session()->flash('error','Brand not found');
            return response()->json([
                'status'=>true,
                'error' => 'Brand not found'
            ]);
        }
        
        $brand->delete();
        $request->session()->flash('success','Brand deleted successfully');
        return response()->json([
            'status'=> true,
            'success'=> 'Brand deleted successfully' 
        ]);
    }
}
