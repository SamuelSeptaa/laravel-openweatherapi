<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Login extends Controller
{
    public function index()
    {
        $this->data['script']           = 'login.script.index';
        return $this->renderTo('login.index');
    }

    public function loging_in(Request $request)
    {
        $credentials = $request->validate(
            [
                'email' => ['required', 'email:dns'],
                'password' => ['required']
            ]
        );

        if (Auth::attempt($credentials, $request->remember)) {
            return response()->json([
                'status'            => 'Success',
                'message'           => 'Login Success',
                'data'              => [
                    'redirect_url'      => route('home')
                ]
            ], 201);
        }
        return response()->json([
            'status'            => 'Failed',
            'message'           => 'Invalid login credentials'
        ], 401);
    }

    public function api_login(Request $request)
    {
        $credentials        = $request->validate(
            [
                'email'        => 'required', 'email:dns',
                'password'      => 'required'
            ]
        );

        if (auth()->attempt($credentials)) {
            $user = auth()->user();

            return response()->json([
                'status'        => 'Success',
                'message'       => 'Login Success',
                'data'          => [
                    'user'      => $user,
                    'token'     => $user->createToken('weatherAPIToken')->plainTextToken,
                ]
            ], 201);
        }

        return response()->json([
            'message' => 'Your credential does not match.',
        ], 401);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function api_logout()
    {
        Auth::user()->tokens()->delete();
        return response()->json([
            'status'        => 'Success',
            'message'       => 'Logout success'
        ]);
    }
}
