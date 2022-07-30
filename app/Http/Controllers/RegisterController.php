<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function create(Request $request)
    {
        return view('user.register');
    }

    public function store(Request $request)
    {
        $input = $request->all();

        $rules     = [
            'user_name'            => ['required'],
            'user_email'           => ['required'],
            'user_type'            => ['required'],
            'mobile'               => ['required'],
            'passed_out_year'      => ['required'],
            'branch'               => ['required'],
            'college'              => ['required'],
            'current_company_name' => ['required_if:user_type,==,experience'],
            'designation'          => ['required_if:user_type,==,experience'],
            'location'             => ['required_if:user_type,==,experience'],
            'password'             => ['required'],
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

        $store = User::store_user($input);

        if ($store) {
            return response()->json([
                'http_code' => 200,
                'message'   => 'User registered successfully'
            ]);
        }

        return response()->json([
            'http_code' => 400,
            'message'   => 'Somthing went wrong'
        ]);
    }
}
