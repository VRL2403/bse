<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function user_list(Request $request)
    {
        $users = User::where('status', 1)
            ->get();
        return view('admin.users', compact('users'));
    }
}
