<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;

class ApiAuthController extends Controller
{
    
    public function signup(Request $request)
    {
        $user = new User;
    }
}
