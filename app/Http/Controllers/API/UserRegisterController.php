<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\UserRegisterRequest;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserRegisterController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(UserRegisterRequest $request)
    {
        $payload = $request->validated();
        try {
            $user = User::create(array_merge($payload, [
                'password' => Hash::make($payload['password']),
                'avatar' => 'https://i.pravatar.cc/300?u='. Str::random(5),
                'role' => 'member'
            ]));
            unset($payload['password']);

            return response()->json(array_merge([
                'message' => 'user register success',
                'token' => $user->createToken($payload['email'])->plainTextToken
            ], $payload));
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 500);
        }
    }
}
