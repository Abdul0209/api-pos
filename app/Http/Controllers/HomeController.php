<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Session;
class HomeController extends Controller
{
    public function resetPassword($token)
    {
        $user = User::where('token',$token)->first();

        return view('home.resetpass', [
            'user' => $user
        ]);
    }
    public function updatePassword(Request $request)
    {
        
        $request->validate([
            'password' => 'required|string|min:6',
        ]);

        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();
        Session::flash('success','Password Anda Berhasil di Rubah');
        return redirect()->back();

    }
}
