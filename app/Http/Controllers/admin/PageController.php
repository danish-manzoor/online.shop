<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PageController extends Controller
{
    public function index(Request $request){
        $page = Page::latest();
        if(!empty($request->get('keyword'))){
            $page->where('name','like','%'.$request->get('keyword').'%');
            $page->orWhere('slug','like','%'.$request->get('keyword').'%');
        }
        $data['pages'] = $page->paginate(10);
        return view('admin.pages.list',$data);
    }


    public function create(){
        return view('admin.pages.create');
    }


    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required|unique:pages'
        ]);

        if($validator->passes()){
            $page = new Page();
            $page->name = $request->name;
            $page->slug = $request->slug;
            $page->content = $request->content;
            $page->status = $request->status;
            $page->save();

            session()->flash('success','Page added successfully');
            return response()->json([
                'status'=> true,
                'errors'=> 'Page added successfully'
            ]);

        }else{
            return response()->json([
                'status'=> false,
                'errors'=> $validator->errors()
            ]);
        }
    }


    public function edit($userId, Request $request){
        $page = Page::find($userId);
        if(empty($page)){
            return redirect()->route('page.list')->with('error','Page not found');
        }
        return view('admin.pages.edit',compact('page'));
    }


    public function update($id,Request $request){
        $page = Page::find($id);
        if(empty($page)){
            session()->flash('error','page not found');
            return response()->json([
                'status'=> true,
                'NotFound'=> true,
                'message'=> "page not found"
            ]);
        }
     
            $validator = Validator::make($request->all(),[
                'name' => 'required',
                'slug' => 'required|unique:pages,slug,'.$page->id.''
            ]);
           
            if($validator->passes()){
                
                $page->name = $request->name;
                $page->slug = $request->slug;
                $page->content = $request->content;
                $page->status = $request->status;
                $page->save();

                session()->flash('success','Page updated successfully');

                return response()->json([
                    'status'=> true,
                    'errors'=> 'Page updated successfully'
                ]);
                
            }else{
                return response()->json([
                    'status'=> false,
                    'errors'=> $validator->errors()
                ]);
            }
    }


    public function destroy($id,Request $request){
        
        $page = Page::find($id);
        // dd($page);
        if(empty($page)){
            session()->flash('error','Page not found');
            return response()->json([
                'status'=>true,
                'error' => 'Page not found'
            ]);
        }
        
        $page->delete();
        session()->flash('success','Page deleted successfully');
        return response()->json([
            'status'=> true,
            'success'=> 'Page deleted successfully' 
        ]);
    }
}
