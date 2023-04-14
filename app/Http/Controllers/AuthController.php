<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Passport\Passport;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = [
            'email'    => $request['email'],
            'password' => $request['password']
        ];

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $userRole = $user->role()->first();
            if (!empty($userRole)) {
                $this->scope = $userRole->role;
            }

            $response['status']        = true;
            $response['message']       = 'Success';
            $response['data']['token'] = 'Bearer ' . $user->createToken('Justc', [$this->scope])->accessToken;

            return response()->json($response, 200);
        } else {
            $response['status']  = false;
            $response['message'] = 'Unauthorized';

            return response()->json($response, 401);
        }
    }

    public function register(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name'     => ['string', 'required'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'role'     => ['required', 'string', 'in:admin,user']
        ]);

        if ($validate->fails()) {
            $response['status']  = false;
            $response['message'] = 'Failed';
            $response['error']   = $validate->errors();

            return response()->json($response, 422);
        }

        DB::beginTransaction();
        try {
            $user = User::create([
                'name'     => $request['name'],
                'email'    => $request['email'],
                'password' => Hash::make($request['password']),
            ]);

            $response['status']  = true;
            $response['message'] = 'Success';
            $response['data']    = [];
            DB::commit();
        } catch (\Throwable $th) {
            $response['status']  = true;
            $response['message'] = 'Failed';
            $response['data']    = $th->getMessage();
            DB::rollBack();
        }


        return response()->json($response, 200);
    }
}
