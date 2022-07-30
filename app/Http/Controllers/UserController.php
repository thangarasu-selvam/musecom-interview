<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function user_list(Request $request)
    {
        $logged_in = session()->get('login');

        if(empty($logged_in) || $logged_in == '') {
           return redirect('/admin/login');
        }
        $user_list = User::user_list();
        return view('admin.user.list', compact('user_list'));
    }

}
