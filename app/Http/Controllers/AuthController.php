<?php

namespace App\Http\Controllers;

use App\Http\Requests\LogIn;
use App\Http\Requests\SignUp;
use App\Http\Resources\UserResource;
use App\Notifications\SignupActivate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;

class AuthController extends Controller
{
    public function signup(SignUp $request)
    {
        $user = new User(
            [
                'name'                 => $request->name,
                'phone_number'         => $request->phone_number,
                'email'                => $request->email,
                'terms_and_conditions' => $request->terms_and_conditions,
                'password'             => bcrypt($request->password),
                'activation_token'     => str_random(60),
                'app_id'               => 1
            ]
        );
        $user->save();

        $user->notify(new SignupActivate($user));

        return response()->json(
            [
                'message' => 'Successfully created user!'
            ],
            201
        );
    }

    public function login(LogIn $request)
    {
        //        dd($request->user());
        //        $request->user()->token()->revoke();

        $credentials = request(['email', 'password']);
        $credentials['active'] = 1;
        $credentials['deleted_at'] = null;

        if (!Auth::attempt($credentials)) {
            return response()->json(
                [
                    'message' => 'Unauthorized'
                ],
                401
            );
        }

        $user = User::where('email', $request->input('email'))->first();
        $user->tokens()->where('revoked', false)->update(
            [
                'revoked'    => true,
                'updated_at' => Carbon::now(),
            ]
        );

        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if ($request->remember_me) {
            $token->expires_at = Carbon::now()->addWeeks(1);
        }
        $token->save();

        return response()->json(
            [
                'access_token' => $tokenResult->accessToken,
                'token_type'   => 'Bearer',
                'expires_at'   => Carbon::parse(
                    $tokenResult->token->expires_at
                )->toDateTimeString()
            ]
        );
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json(
            [
                'message' => 'Successfully logged out'
            ]
        );
    }

    public function signupActivate($token)
    {
        $user = User::where('activation_token', $token)->first();
        if (!$user) {
            return response()->json(
                [
                    'message' => 'This activation token is invalid.'
                ],
                404
            );
        }
        $user->active = true;
        $user->activation_token = '';
        $user->save();

        return $user;
    }

//    public function updateUser(Request $request)
//    {
//        $request->validate(
//            [
//                'email'        => 'required|string|email',
//                'password'     => 'required|string',
//                'name'         => 'required|string',
//                'phone_number' => 'required|string',
//            ]
//        );
//
//        $credentials = request(['email', 'password']);
//        $credentials['active'] = 1;
//        $credentials['deleted_at'] = null;
//
//        if (!Auth::attempt($credentials)) {
//            return response()->json(
//                [
//                    'message' => 'Unauthorized'
//                ],
//                401
//            );
//        }
//
//        $user = User::where('email', $request->input('email'))->first();
//        $user->name = $request->name;
//        $user->phone_number = $request->phone_number;
//        $user->save();
//
//        return response()->json($user);
//    }
//
//    public function user()
//    {
//        return $this->out(new UserResource(auth()->user()));
//    }
}
