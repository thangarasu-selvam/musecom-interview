<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function login(Request $request)
    {
        $logged_in = session()->get('login');

        if(!empty($logged_in) || $logged_in != '') {
           return redirect('/admin/user/list');
        }
        return view('admin.login');
    }

    public function check_login(Request $request)
    {
        $input     = $request->all();
        $rules     = [
            'user_email' => ['required'],
            'password'   => ['required'],
        ];
        $validator = app('validator')->make($input, $rules);

        if ($validator->fails()) {
            foreach ($validator->errors()->messages() as $key => $value) {
                $error[] = is_array($value) ? implode(',', $value) : $value;
            }
            $errors = implode(',', $error);
            return response()->json([
                'http_code' => 400,
                'message'   => $errors
            ]);
        }
        if ($input['user_email'] == 'test@gmail.com' && $input['password'] == 'test') {
            session()->put('login', true);
            return response()->json([
                'http_code' => 200,
                'message'   => 'Login successfully'
            ]);
        }
        session()->put('login', false);
        return response()->json([
            'http_code' => 400,
            'message'   => 'Something went wrong'
        ]);
    }

}
