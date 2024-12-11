<?php

namespace App\Http\Controllers;

use App\Models\Token;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TokenController extends Controller
{
    public function createToken()
    {
        try {
            $userName = $_SERVER['AUTH_USER'];
            if (is_null($userName)) return null;

            $tokenValue = Str::random(64);

            $token = Token::create([
                'token' => $tokenValue,
                'user_name' => $userName
            ]);

            $html = '<script>setTimeout(() => {const message = "' . $tokenValue . '";window.parent.postMessage(message, "*")}, 1000);</script>';

            return $html;
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }

    public function verifyToken($userToken)
    {
        $token = Token::where('token', $userToken)->where('revoked', 0)->first();
        if (!$token) return null;

        $user = User::where('user_name', $token->user_name)->first();
        if (!$user) return null;

        $token->update(['revoked' => 1]);

        return $user;
    }
}
