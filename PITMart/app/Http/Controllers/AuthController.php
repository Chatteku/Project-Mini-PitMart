<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all() , [
            'name' => 'required|max:191',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:5|confirmed'
        ]);

        if($validator->fails())
        {
            return response()->json([
                'validation_gagal' => $validator->messages(),
            ]);
        }
        else
        {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ])->sendEmailVerificationNotification();

            // $token = $user->createToken($user->email . '_Token')->plainTextToken;

            return response()->json([
                'status' => 200,
                // 'name' => $user->name,
                // 'email' => $user->email,
                // 'token' => $token,
                'messages' => 'Register Berhasil'
            ]);
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all() , [
            'email' => 'required|max:191',
            'password' => 'required',
        ]);

        if($validator -> fails())
        {
            return response()->json([
                'status' => 402,
                'messages' => $validator->messages(),
            ]);
        }
        else
        {
            $user = User::where('email' , $request->email)->first();

            if(! $user || !Hash::check($request->password  ,$user->password))
            {
                return response()->json([
                    'status' => 401,
                    'messages' => 'Invalid Credentials'
                ]);
            }
            else
            {
                if($user->role_as == 1)
                {
                    $token = $user->createToken($user->email . 'AdminToken' , ['server:admin'])->plainTextToken;

                    return response()->json([
                        'status' => 200,
                        'name' => $user->name,
                        'email' => $user->email,
                        'role_as' => $user->role_as,
                        'token' => $token,
                        'messages' => 'Logged In SuccesFully , Irasshaimasu Admin',
                    ]);
                }
                else
                {
                    $token = $user->createToken($user->email . '_Token' , [''])->plainTextToken;

                    return response()->json([
                        'status' => 200,
                        'name' => $user->name,
                        'email' => $user->email,
                        'token' => $token,
                        'role_as' => $user->role_as,
                        'messages' => 'Logged in Succesfully'
                    ]);
                }
            }
        }
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();
        return response()->json([
        'status' => 400,
        'messages' => 'Logged Out'
    ]);
     }
}
