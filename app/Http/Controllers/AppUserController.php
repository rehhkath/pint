<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log; 
use Illuminate\Support\Facades\Hash;
use App\Models\AppUser;

class AppUserController extends Controller
{
    public function login(Request $request)
    {
        Log::info('Login attempt', [
            'username' => $request->username
        ]);
        
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = AppUser::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'Credenciais inválidas'], 401);
        }

        $token = Str::random(60);
        $user->api_token = $token;
        $user->save();

        return response()->json([
            'user' => $user->only(['id', 'username', 'api_token', 'role', 'stores', 'cigam_id']),
        ]);
    }
}
