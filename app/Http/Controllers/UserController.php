<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function create(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
        ]);

        $data['password'] = Str::before($data['email'], '@');

        $user = User::query()->create($data);

        return response()->json($user, Response::HTTP_NO_CONTENT);
    }

    public function update(int $id, Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
        ]);

        $user = User::query()->findOrFail($id);
        $user->update($data);

        return response()->json($user, Response::HTTP_OK);
    }

    public function updatePassword(int $id, Request $request)
    {
        $data = $request->validate([
            'password' => Password::defaults(),
        ]);

        $password = $data['password'];

        $user = User::query()->findOrFail($id);
        $user->update(['password' => $password]);

        return response()->json($user, Response::HTTP_OK);
    }

    public function getAll()
    {
        $data = User::query()->paginate();

        return response()->json($data, Response::HTTP_OK);
    }

    public function getById(int $id)
    {
        $user = User::query()->findOrFail($id);

        return response()->json($user, Response::HTTP_OK);
    }

    public function delete(int $id)
    {
        $user = User::query()->findOrFail($id);
        $user->delete();

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
