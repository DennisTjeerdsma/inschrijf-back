<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Auth;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware(['jwt.auth', 'clearance']);
    }

    public function load(Request $request)
    {

        $userlist = User::all();

        foreach ($userlist as $user)
        {
            $user['role'] = $user->getRoleNames()->first();
        }
        return response()->json($userlist);
    }

    public function loadroles(Request $request)
    {
        return response()->json([Role::all()->pluck('name')]);
    }

    public function destroy($userId)
    {
        $user = User::findOrFail($userId);
        $user['role'] = $user->getRoleNames()->first();
        if ($user['role'] = 'Super Admin')
        {
            abort('401');
        }
        else {
            $user->delete();
            return response()->json($user);
    
        }
    }

    public function multidestroy(Request $request)
    {
        $idArray = $request->all();

        foreach ($idArray as $id)
        {
            $user = User::findorFail($id);
            $user['role'] = $user->getRoleNames()->first();

            if ($user['role'] != 'Super Admin')
            {
                $user->delete();
            }
        }

        return response()->json($idArray);
    }

    public function create(Request $request)
    {
      $validator = Validator::make($request->all(), [
          'name'  => 'required|max:255',
          'email' => 'required|email|unique:users',
        ]);
        // If validator fails, short circut and redirect with errors
        if($validator->fails()){
          return back()
            ->withErrors($validator)
            ->withInput();
        }
        //generate a password for the new users
        $pw = User::generatePassword();
        //add new user to database
        $user = new User;
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = $pw;
        $user->assignRole('Gebruiker');
        $user->save();
        User::sendWelcomeEmail($user);
    }
}
