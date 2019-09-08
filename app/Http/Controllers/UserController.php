<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
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
}
