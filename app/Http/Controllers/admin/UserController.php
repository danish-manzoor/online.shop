<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(Request $request){
        $users = User::latest();
        if(!empty($request->get('keyword'))){
            $users->where('name','like','%'.$request->get('keyword').'%');
            $users->orWhere('email','like','%'.$request->get('keyword').'%');
        }
        $data['users'] = $users->paginate(10);
        // dd($request->get('keyword'));
        return view('admin.users.list',$data);
    }


    public function create(){
        // die('dfa');
        return view('admin.users.create');
    }


    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|unique:users',
            'phone' => 'required',
            'password'=> 'required|min:6'
        ]);
        if($validator->passes()){
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->password = Hash::make($request->password);
            $user->status = $request->status;
            $user->save();

            // $request->session()->flash();
            session()->flash('success','User added successfully');

            return response()->json([
                'status'=> true,
                'errors'=> 'User added successfully'
            ]);
        }else{
            return response()->json([
                'status'=> false,
                'errors'=> $validator->errors()
            ]);
        }
    }


    public function edit($userId, Request $request){
        $user = User::find($userId);
        if(empty($user)){
            return redirect()->route('users.list')->with('error','User not found');
        }
        return view('admin.users.edit',compact('user'));
    }


    public function update($id,Request $request){
        $user = User::find($id);
        if(empty($user)){
            session()->flash('error','User not found');
            return response()->json([
                'status'=> true,
                'NotFound'=> true,
                'message'=> "User not found"
            ]);
        }
     
            $validator = Validator::make($request->all(),[
                'name' => 'required',
                'email' => 'required|unique:users,email,'.$user->id.',id',
                'phone' => 'required'
            ]);
           
            if($validator->passes()){
                $user->name = $request->name;
                $user->email = $request->email;
                $user->status = $request->status;
                $user->phone = $request->phone;
                if(!empty($request->password)){
                    $user->password = Hash::make($request->password);
                }
                $user->save();

                session()->flash('success','User updated successfully');

                return response()->json([
                    'status'=> true,
                    'errors'=> 'User updated successfully'
                ]);
                
            }else{
                return response()->json([
                    'status'=> false,
                    'errors'=> $validator->errors()
                ]);
            }
    }


    public function destroy($id,Request $request){
        
        $user = User::find($id);
        if(empty($user)){
            session()->flash('error','User not found');
            return response()->json([
                'status'=>true,
                'error' => 'User not found'
            ]);
        }
        
        $user->delete();
        session()->flash('success','User deleted successfully');
        return response()->json([
            'status'=> true,
            'success'=> 'User deleted successfully' 
        ]);
    }
}
