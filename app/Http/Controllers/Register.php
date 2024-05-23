<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Register extends Controller
{
    public function index()
    {
        $this->data['script']           = 'register.script.index';
        return $this->renderTo('register.index');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'              => ['required', 'min:5', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
            'email'             => ['required', 'min:5', 'max:255', 'unique:users,email', 'email:dns'],
            'password'          => ['required', 'min:5', 'max:255'],
            'password_confirm'  => ['same:password'],
        ]);

        DB::beginTransaction();
        try {
            $member     = User::create([
                'name'                  => $request->name,
                'email'                 => $request->email,
                'password'              => bcrypt($request->password),
            ]);

            DB::commit();
            return response()->json([
                'status'        => 'Success',
                'message'       => "Registration success, you can now <a href=" . route('login') . ">Login</a>"
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'Failed',
                'message'   => "Transaction failed: " . $e->getMessage()
            ], 500);
        }
    }
}
