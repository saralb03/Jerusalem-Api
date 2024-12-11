<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller {
    
    public function login(Request $reuqest): JsonResponse { 
        $personalNumber = $reuqest->personal_number;

        $user = User::where('personal_number', $personalNumber)->first();
        
        if(is_null($user)) {
            return response()->json(['message' =>'עובד לא נמצא.'], Response::HTTP_NOT_FOUND);
        }

        $tokenName = config('auth.token_name');
        
        // revoke old tokens $user->
        $token = $user->createToken($tokenName);
        
        return response()->json([
            ['message' =>'התחברת בהצלחה.'], Response::HTTP_OK
        ])->withCookie(Cookie::make($tokenName, $token->accessToken));
    }
}