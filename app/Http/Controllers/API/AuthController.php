<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    function login(AuthRequest $request)
    {
        $payload = $request->validated();
        try {
            if (!auth()->attempt([
                'email' => $payload['email'],
                'password' => $payload['password'],
            ])) {
                throw new \Exception('Invalid Credentials', 400);
            }

            $access_token = auth()->user()->createToken(
                $payload['email']
            );
            unset($payload['password']);

            return response(array_merge([
                'token' => $access_token->plainTextToken,
                'first_name' => auth()->user()->first_name,
                'last_name' =>  auth()->user()->last_name,
            ], $payload));
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage()
            ],$e->getCode() == 0 ? 500 : $e->getCode());
        }
    }

    public function logout(Request $request)
    {
        $revoke = $request->user()->token()->revoke();
        return response([
            'message' => $revoke ? "OK" : "something wrongs"
        ], $revoke ? 200 : 500
        );
    }
}
