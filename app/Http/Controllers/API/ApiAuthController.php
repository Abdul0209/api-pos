<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


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
            'status' => 'success',
            'data' => $user
        ]);
    }
}
