<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    public function showChangePasswordForm(){
        return view('admin.settings.change-password');
    }


    public function changePassword(Request $request){
        $validator = Validator::make($request->all(),[
            'old_password' => 'required',
            'new_password' => 'required',
            'confirm_password'=> 'required|same:new_password'
           ]);
    
           if($validator->passes()){
            $user = User::find(Auth::guard('admin')->user()->id);
            
            if(Hash::check($request->old_password,$user->password)){
                $user->password = Hash::make($request->new_password);
                $user->save();
                session()->flash('success','Passwrod has been changed');
                return response()->json([
                    'status' => true,
                    'errors' => "Passwrod has been changed"
                ]);
            }else{
                session()->flash('error','The old password is incorrect');
                return response()->json([
                    'status' => true,
                    'errors' => "The old password is incorrect"
                ]);
            }
           }else{
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors()
                ]);
           }
    }
}
