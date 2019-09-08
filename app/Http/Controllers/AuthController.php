<?php

namespace App\Http\Controllers;
use App\Http\Requests\RegisterFormRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use JWTAuth;
use Spatie\Permission\Models\Role;


class AuthController extends Controller
{
    public function login(Request $request)
    {
    $credentials = $request->only('email', 'password');    
    
    if ( ! $token = JWTAuth::attempt($credentials)) {
            return response([
                'status' => 'error',
                'error' => 'invalid.credentials',
                'msg' => 'Invalid Credentials.'
            ], 400);
        }    
    
        return response()->json([
            'token' => $token,
            'type' => 'bearer', // you can ommit this
            'expires' => auth('api')->factory()->getTTL() * 60, // time to expiration
            
        ]);
    }

    public function user(Request $request)
    {
    $user = User::find(Auth::user()->id);
    $user['role'] = $user->getRoleNames()->first();    
    return response($user);
    }
    
    public function refresh()
    {
    return response([
            'status' => 'success'
    ]);
    }

    public function logout()
    {
        JWTAuth::invalidate();    return response([
            'status' => 'success',
            'msg' => 'Logged out Successfully.'
            ], 200);
    }
}


