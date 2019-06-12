<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    public function updateUser(UpdateUserRequest $request)
    {
        $user = auth()->user();
        $user->name = $request->name;
        $user->phone_number = $request->phone_number;
        $user->save();

        return $this->out($user);
    }

    public function user()
    {
        return $this->out(new UserResource(auth()->user()));
    }
}
