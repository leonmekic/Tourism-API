<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    public function updateUser(UpdateUserRequest $request)
    {
        $user = auth()->user();
        $user->name = $request->input('name');
        $user->phone_number = $request->input('phone_number');
        $user->save();

        return $this->out(new UserResource($user));
    }

    public function user()
    {
        return $this->out(new UserResource(auth()->user()));
    }
}
