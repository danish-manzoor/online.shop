<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubCategorController extends Controller
{
    public function index(Request $request){
        $subcategories = SubCategory::select('sub_categories.*','categories.name as categoryName')
                            ->latest('sub_categories.id')
                            ->leftjoin('categories','categories.id','sub_categories.category_id');
        if(!empty($request->get('keyword'))){
            $subcategories->where('sub_categories.name','like','%'.$request->get('keyword').'%');
        }
        $data['subcategories'] = $subcategories->paginate(10);
        // dd($request->get('keyword'));
        return view('admin.sub_category.list',$data);
    }


    public function create(){
        $categories= Category::orderBy('name')->get();
        return view('admin.sub_category.create',compact('categories'));
    }



    public function store(Request $request){

        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required|unique:sub_categories',
            'category' => 'required'
        ]);

        if($validator->passes()){
            $sub_category = new SubCategory();
            $sub_category->name = $request->name;
            $sub_category->slug = $request->slug;
            $sub_category->status = $request->status;
            $sub_category->category_id = $request->category;
            $sub_category->showHome = $request->showHome;
            $sub_category->save();


           
            // $request->session()->flash();
            session()->flash('success','Sub-Category added successfully');

            return response()->json([
                'status'=> true,
                'errors'=> 'sub-Category added successfully'
            ]);
        }else{
            return response()->json([
                'status'=> false,
                'errors'=> $validator->errors()
            ]);
        }
    }



    public function edit($id, Request $request){
        $categories= Category::orderBy('name')->get();
        $subcategories = SubCategory::find($id);
        if(empty($subcategories)){
            return redirect()->route('sub-category.list')->with('error','Sub Category not found');
        }
        return view('admin.sub_category.edit',compact('subcategories','categories'));
    }


    public function update($id,Request $request){
        
        $subcategories = SubCategory::find($id);
        
        
        if(empty($subcategories)){
            session()->flash('error','Sub Cateogry not found');
            return response()->json([
                'status'=> true,
                'NotFound'=> true,
                'message'=> "Sub Category not found"
            ]);
        }
     
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required|unique:sub_categories,slug,'.$subcategories->id.',id',
            'category' => 'required'
        ]);
           
            if($validator->passes()){
                
                $subcategories->name = $request->name;
                $subcategories->slug = $request->slug;
                $subcategories->status = $request->status;
                $subcategories->category_id = $request->category;
                $subcategories->showHome = $request->showHome;
                $subcategories->save();

                session()->flash('success','Sub Category updated successfully');

                return response()->json([
                    'status'=> true,
                    'errors'=> 'Sub Category updated successfully'
                ]);
                
            }else{
                return response()->json([
                    'status'=> false,
                    'errors'=> $validator->errors()
                ]);
            }
    }


    public function destroy($id,Request $request){
        
        $subcategory = SubCategory::find($id);
        // dd($category);
        if(empty($subcategory)){
            session()->flash('error','sub Category not found');
            return response()->json([
                'status'=>true,
                'error' => 'sub Category not found'
            ]);
        }
        $subcategory->delete();
        session()->flash('success','sub Category deleted successfully');
        return response()->json([
            'status'=> true,
            'success'=> 'sub Category deleted successfully' 
        ]);
    }
}
