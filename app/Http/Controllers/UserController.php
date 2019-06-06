<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function updateUser(Request $request)
    {
        $request->validate(
            [
                'email'        => 'required|string|email',
                'password'     => 'required|string',
                'name'         => 'required|string',
                'phone_number' => 'required|string',
            ]
        );

        $credentials = request(['email', 'password']);
        $credentials['active'] = 1;
        $credentials['deleted_at'] = null;

        if (!Auth::guard('web')->attempt($credentials)) {
            return response()->json(
                [
                    __('user.unauthorized')
                ],
                401
            );
        }

        $user = User::where('email', $request->input('email'))->first();
        $user->name = $request->name;
        $user->phone_number = $request->phone_number;
        $user->save();

        return response()->json($user);
    }

    public function user()
    {
        return $this->out(new UserResource(auth()->user()));
    }
}
