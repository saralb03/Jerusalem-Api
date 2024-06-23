<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller {

    public function show(Request $request): JsonResponse {
        $personalNumber = $request->query('personal_number');
        if(is_null($personalNumber)) {
            $users = User::select('user_name', 'personal_number', 'population_id')->get();
            return response()->json($users, Response::HTTP_OK);
        }

        $user = User::where('personal_number', $personalNumber)->first();
        if(is_null($user)) {
            return response()->json('המשתמש המבוקש אינו קיים', Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'username' => is_null($user) ? null : $user->user_name
        ], Response::HTTP_OK);
    }
}
