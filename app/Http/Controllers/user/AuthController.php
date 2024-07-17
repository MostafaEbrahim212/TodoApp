<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register()
    {
        return view('auth.register');
    }
    public function login()
    {
        return view('auth.login');
    }
    public function postLogin(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => true, 'message' => $validator->errors()->all()]);
        }
        $credentials = $request->only('email', 'password');

        if (auth()->attempt($credentials)) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['error' => true, 'message' => 'Invalid credentials']);
        }
    }

    public function postRegister(Request $request)
    {
        try {
            $validator = \Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6|confirmed'
            ]);
            if ($validator->fails()) {
                return response()->json(['error', 'message' => $validator->errors()->first()]);
            }
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password)
            ]);
            auth()->login($user);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error', 'message' => $e->getMessage()]);
        }
    }

    public function logout()
    {
        auth()->logout();
        return redirect()->route('login');
    }


}
