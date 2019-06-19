<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\LogInRequest;
use App\Http\Requests\User\SignUpRequest;
use App\Notifications\SignupActivate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;

class AuthController extends Controller
{
    public function signup(SignUpRequest $request)
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

        return $this->out(
            [],
            __('user.created'),
            201
        );
    }

    public function login(LogInRequest $request)
    {
        $credentials = request(['email', 'password']);
        $credentials['deleted_at'] = null;

        if (!Auth::attempt($credentials)) {
            return $this->outWithError(
                __('user.unauthorized'),
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

        return $this->out(
            [
                'access_token' => $tokenResult->accessToken,
                'token_type'   => 'Bearer',
                'expires_at'   => Carbon::parse(
                    $tokenResult->token->expires_at
                )->toDateTimeString()
            ],
            __('user.login')
        );
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return $this->out(
            [],
            __('user.logout')

        );
    }

    public function signupActivate($token)
    {
        $user = User::where('activation_token', $token)->first();
        if (!$user) {
            return $this->outWithError(
                __('user.invalid-token'),
                404
            );
        }
        $user->active = true;
        $user->activation_token = '';
        $user->save();

        return $this->out([], __('user.activated'));
    }
}
