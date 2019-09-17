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
        $user->delete();
        $user['role'] = $user->getRoleNames()->first();
        return response()->json($user);

    }

    public function multidestroy(Request $request)
    {
        $idArray = $request->all();

        foreach ($idArray as $id)
        {
            $user = User::findorFail($id);
            $user->delete();
        }

        return response()->json($idArray);
    }
}
