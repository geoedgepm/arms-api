<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Str;
use App\Models\User;

class AuthController extends BaseController
{
    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function authenticate(Request $request)
    {
        $phone_number = $request->header('account');
        $password = $request->header('password');

        $validator = Validator::make(['phone_number' => $phone_number, 'password' => $password], [
            'phone_number' => 'required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->responseError('bad_request', 'Please provide phone number and password');
        }

        if (Auth::attempt(['email' => $phone_number, 'password' => $password])) {
            $token = hash('sha256', Str::random(60));

            $user = Auth::user();

            return response()->json([
                'access_token' => $token,
                'profile' => [
                    'id'             => $user->id,
                    'name'           => $user->name,
                    'type'           => $user->type,
                    'unit'           => $user->unit ? $user->unit->name : '',
                    'unit_short_name'=> $user->unit ? $user->unit->short_name : '',
                    'phone_number'   => $user->email,
                    'photo'          => $user->avatar,
                    'province_id'    => $user->province_id,
                    'province'       => $user->province ? $user->province->name_km : '',
                    'attachment1'    => $user->reporterAccount ? $user->reporterAccount->attachment1 : '',
                    'attachment2'    => $user->reporterAccount ? $user->reporterAccount->attachment2 : ''
                ]
            ]);
        }

        return $this->responseError('forbiden', 'Invalid username or password');
    }

    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        // return User::where([
        //     'EmailID' => 'abharata@deloitte.com'
        // ])
        // ->update([
        //     'Password' => bcrypt('123456')
        // ]);

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        
        $token = Auth::guard('api')->attempt(['EmailID' => 'abharata@deloitte.com', 'Password' => '123456']);
        return ['token' => $token, 'account' => $credentials['email'], 'password' => $credentials['password']];
        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        $user = Auth::guard('api')->user();
    
        return response()->json([
                'status' => 'success',
                'user' => $user,
                'authorization' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
            ]);

    }

    public function logout()
    {
        Auth::guard('api')->logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }


    public function refresh()
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::guard('api')->user(),
            'authorisation' => [
                'token' => Auth::guard('api')->refresh(),
                'type' => 'bearer',
            ]
        ]);
    }
}