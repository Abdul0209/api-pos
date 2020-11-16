<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Mail\ForgetPassword;
use Illuminate\Support\Facades\Mail;

class ApiAuthController extends Controller
{
    
    public function signup(Request $request)
    {
      
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            
        ]);
       
        if ($validator->fails()) {    
            return response()->json([
                'status' => 'Error',
                'data' => $validator->messages()->first()
            ]);
            die();
        }

        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->token = Str::random(40);
        $user->save();

        return response()->json([
            'status' => 'Success',
            'data' => $user
        ]);
    }
    public function login(Request $request)
    {
        $email = $request->email;
        $password = $request->password;

        $user = User::where('email', '=', $email)->first();
        if (!$user) {
            return response()->json(['status'=>'error', 'message' => 'Login Fail, please check email id']);
        }
        if (!Hash::check($password, $user->password)) {
            return response()->json(['status'=>'error', 'message' => 'Login Fail, pls check password']);
        }   
            return response()->json(['status'=>'Success','message'=>$user]);


    }
    public function forget(Request $request)
    {
        $user = User::where('email',$request->email)->first();
        if (!$user) {
            return response()->json(['status'=>'Success', 'message' => "reset link telah dikirimkan ke email, silahkan cek email Anda"]);
        }
        // dd($user->email);
        Mail::to($user->email)->send(new ForgetPassword($user));
        return response()->json([
            'status' => 'Success',
            'message' => "reset link telah dikirimkan ke email, silahkan cek email Anda"
        ]);
    }
}
