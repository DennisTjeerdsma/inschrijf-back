<?php

namespace App\Http\Controllers;
use App\Http\Requests\RegisterFormRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use JWTAuth;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Foundation\Auth\ResetsPasswords;


class AuthController extends Controller
{

    use SendsPasswordResetEmails, ResetsPasswords {
        SendsPasswordResetEmails::broker insteadof ResetsPasswords;
        ResetsPasswords::credentials insteadof SendsPasswordResetEmails;
    }

    public function login(Request $request)
    {
            // get email and password from request
        $credentials = request(['email', 'password']);
        
            // try to auth and get the token using api authentication
        if (!$token = auth('api')->attempt($credentials)) {
                // if the credentials are wrong we send an unauthorized error in json format
            return response()->json(['error' => 'Unauthorized'], 401);
            }  
    
        return response()->json([
            'token' => $token,
            'type' => 'bearer', // you can ommit this
            'expires' => auth('api')->factory()->getTTL() * 60, // time to expiration
            
        ])->header('Authorization', $token);
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

    public function sendPasswordResetLink(Request $request)
    {
    return $this->sendResetLinkEmail($request);
    }

    protected function sendResetLinkResponse(Request $request, $response)
    {
    return response()->json([
        'message' => 'Password reset email sent.',
        'data' => $response
    ]);
    }

    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
    return response()->json(['message' => 'Email could not be sent to this email address.'], 406);
    }

    public function callResetPassword(Request $request)
    {
    return $this->reset($request);
    }

    protected function resetPassword($user, $password)
    {
    $user->password = Hash::make($password);
    $user->save();
    event(new PasswordReset($user));
    }
    protected function sendResetResponse(Request $request, $response)
    {
        return response()->json(['message' => 'Password reset successfully.']);
    }
    protected function sendResetFailedResponse(Request $request, $response)
    {
        return response()->json(['message' => 'Failed, Invalid Token.'], 401);
    }
    
}


