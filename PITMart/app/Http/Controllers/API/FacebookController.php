<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User; 

class FacebookController extends Controller
{
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->stateless()->redirect()->getTargetUrl();
    }


    public function handleFacebookCallback(Request $request)
    {
        $user = Socialite::driver('facebook')->stateless()->user();
        $existingUser = User::where('email', $user->email)->first();

        if ($existingUser) {
            Auth::login($existingUser);
            $token = $existingUser->createToken('authToken')->accessToken;
        } else {
            // Buat user baru jika belum ada di database
            $newUser = new User();
            $newUser->name = $user->name;
            $newUser->email = $user->email;
            $newUser->password = bcrypt('password'); // Set password default sesuai kebutuhan
            $newUser->save();

            Auth::login($newUser);
            $token = $newUser->createToken('authToken')->accessToken;
        }

        return response()->json(['token' => $token], 200);
    }
}
