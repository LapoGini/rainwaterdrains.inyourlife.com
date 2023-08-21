<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if (empty($credentials['email']) && empty($credentials['password'])) {
            return response()->json(['result' => false, 'error' => 'Credenziali non inserite!'], 401);
        }
        
        if (Auth::guard('web')->attempt($credentials)) {
            $user = Auth::guard('web')
                        ->user()
                        ->setAuthToken();
        

            return response()->json([
                'result' => true, 
                'user' => $user,
            ], 200);
        }

        return response()->json(['result' => false, 'error' => 'User e/o password errate!'], 401);
    }

    public function logout(Request $request)
    {
        $user = Auth::guard('api')->user();
        
        if ($user) {
            $user->api_token = null;
            $user->save();
        }

        return response()->json(['success' => 'Logged out'], 200);
    }
}