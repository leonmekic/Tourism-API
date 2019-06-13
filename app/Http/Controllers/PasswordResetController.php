<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\CreatePasswordResetRequest;
use App\Http\Resources\UserResource;
use App\Models\PasswordReset;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Requests\PasswordResetRequest;
use App\Notifications\PasswordResetRequest as PasswordResetRequestNotification;
use App\Notifications\PasswordResetSuccess;
use App\Models\User;
//use App\Models\PasswordReset;
use Illuminate\Support\Facades\Auth as Auth;
use Illuminate\Support\Facades\Hash;

class PasswordResetController extends Controller
{
    /**
     * Create token password reset
     */
    public function create(CreatePasswordResetRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return $this->outWithError(
                    __('user.invalid-email'),
                404
            );
        }
        $passwordReset = PasswordReset::updateOrCreate(
            ['email' => $user->email],
            [
                'email' => $user->email,
                'token' => str_random(60)
            ]
        );
        if ($user && $passwordReset) {
            $user->notify(
                new PasswordResetRequestNotification($passwordReset->token)
            );
        }

        return $this->out(
            [],
            __('user.password-reset-email')
        );
    }

    /**
     * Find token password reset
     */
    public function find($token)
    {
        $passwordReset = PasswordReset::where('token', $token)->first();
        if (!$passwordReset) {
            return $this->outWithError(
                __('user.invalid-token'),
                404
            );
        }
        if (Carbon::parse($passwordReset->updated_at)->addMinutes(720)->isPast()) {
            $passwordReset->delete();

            return $this->outWithError(
                __('user.invalid-token'),
                404
            );
        }

        return $this->out($passwordReset);
    }

    /**
     * Reset password
     */
    public function reset(PasswordResetRequest $request)
    {
        $passwordReset = PasswordReset::where(
            [
                ['token', $request->token],
                ['email', $request->email]
            ]
        )->first();
        if (!$passwordReset) {
            return $this->outWithError(
                __('user.invalid-token'),
                404
            );
        }
        $user = User::where('email', $passwordReset->email)->first();
        if (!$user) {
            return $this->outWithError(
                __('user.invalid-email'),
                404
            );
        }
        $user->password = bcrypt($request->password);
        $user->save();
        $passwordReset->delete();
        $user->notify(new PasswordResetSuccess($passwordReset));
        $user = User::where('email', $request->input('email'))->first();
        $user->tokens()->where('revoked', false)->update(
            [
                'revoked'    => true,
                'updated_at' => Carbon::now(),
            ]
        );

        return $this->out([], __('user.password-reset-success'));
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        if (!Hash::check($request->password, auth()->user()->getAuthPassword())) {
            return $this->outWithError(
                __('user.unauthorized'),
                401
            );
        }

        $user = auth()->user();
        $user->password = bcrypt($request->new_password);
        $user->save();

        return $this->out($user);
    }
}
