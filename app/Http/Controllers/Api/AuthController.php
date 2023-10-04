<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


class AuthController extends Controller
{
    public function login(Request $request)
    {
        // INIZIO RWD //
        // Prova prima con il token
        $bearerToken = $request->header('Authorization');
        $apiToken = str_replace('Bearer ', '', $bearerToken);
        
        if (!empty($apiToken)) {
            $user = User::where('api_token', $apiToken)->first();
            if ($user) {
                return response()->json(['result' => true, 'user' => $user], 200);
            }
        }
        // FINE RWD //


        // Se nessun token è fornito, prova con email e password
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if (empty($credentials['email']) && empty($credentials['password'])) {
            return response()->json(['result' => false, 'error' => 'Credenziali non inserite!'], 401);
        }
        
        if (Auth::guard('web')->attempt($credentials)) {
            $user = Auth::guard('web')->user();
            $user->setAuthToken();

            return response()->json([
                'result' => true, 
                'user' => $user,
                /* 
                INIZIO GEO.ZA
                'comuni' => (new CityController)->getAll()->original['data'], 
                'vie' => (new CityController)->getViePerOgniComune()->original['data'],
                'clienti' => (new UserController)->getByRole('cliente')->original['data'], 
                'recapiti' => (new TagController)->getRecapiti()->original['data'], 
                'stati' => (new TagController)->getStati()->original['data'], 
                'pozzetti' => (new TagController)->getTipiPozzetto()->original['data']
                FINE GEO.ZA 
                */
            ], 200);
        }

        return response()->json(['result' => false, 'error' => 'User e/o password errate!'], 401);
    }

    public function logout(Request $request)
    {
        $user = User::where('api_token', $request->bearerToken())->first();

        // GEO.ZA    $user = Auth::guard('api')->user();


        if ($user) {
            $user->api_token = null;
            $user->save();
        }

        return response()->json(['success' => 'Logged out'], 200);
    }
}