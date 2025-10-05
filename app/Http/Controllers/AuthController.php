<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }

        $credentials = $request->only('email', 'password');
        $auth = Auth::attempt($credentials);

        if (!$auth) {
            return redirect()->back()->with('error', 'Email or Password is incorrect');
        }

        $user = Auth::user();
    }
}
