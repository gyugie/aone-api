<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $page = $request->get('page', 1);
        $per_page = $request->get('per_page', 10);
        $search = $request->get('search', '');
        $users = User::query();

        if($request->has('search') && $request->get('search') != '') {
            $users->where('first_name', "LIKE","%".$request->get('search')."%")
                ->orWhere('last_name', "LIKE","%".$request->get('search')."%")
                ->orWhere('email', "LIKE","%".$request->get('search')."%");
        }

        $response = $users->orderBy('created_at', 'desc')
            ->paginate($per_page, ['*'], 'page', $page);

        return response()->json( $response );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        $payload = $request->validated();

        try {
            $user = User::create(array_merge($payload, [
                'password' => md5($payload['password']),
                'role' => 'member'
            ]));
            return response()->json(['message' => 'user register success', 'token' => $user->createToken($payload['email'])->plainTextToken]);
        } catch (\Exception $exception) {
            return response()->json(['message' => "User Not Found"], 404);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        try {
            return response()->json(['message' => 'success get post', 'data' => $user]);
        } catch (\Exception $exception) {
            return response()->json(['message' => "User Not Found"], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, User $user)
    {
        $payload = $request->validated();
        try {
            $user->update($payload);
            return response()->json(['message' => 'User updated', 'data' => $user->refresh()]);
        } catch (\Exception $exception) {
            return response()->json(['message' => "Failed to update User"], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            User::find($id)->delete();
            return response()->json(['message' => 'success deleted user', 'data' => []]);
        } catch (\Exception $exception) {
            return response()->json(['message' => "User Not Found"], 404);
        }
    }
}
