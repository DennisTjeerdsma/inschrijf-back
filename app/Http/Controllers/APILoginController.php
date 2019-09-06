<?php

namespace App\Http\Controllers;

use Hash;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Foundation\Auth\ResetsPasswords;
use App\Http\Requests\UserRequest;

class APILoginController extends Controller
{

    use SendsPasswordResetEmails, ResetsPasswords {
        SendsPasswordResetEmails::broker insteadof ResetsPasswords;
        ResetsPasswords::credentials insteadof SendsPasswordResetEmails;
    }
    //Please add this method
    public function login() 
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
            
        ]);
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