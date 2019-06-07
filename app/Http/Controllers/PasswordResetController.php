<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePasswordResetRequest;
use App\Http\Resources\UserResource;
use App\Models\PasswordReset;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Requests\PasswordResetRequest;
use App\Notifications\PasswordResetSuccess;
use App\Models\User;
//use App\Models\PasswordReset;
use Illuminate\Support\Facades\Auth;

class PasswordResetController extends Controller
{
    /**
     * Create token password reset
     */
    public function create(CreatePasswordResetRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(
                [
                    __('user.invalid-email')
                ],
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
                new PasswordResetRequest($passwordReset->token)
            );
        }

        return response()->json(
            [
               __('user.password-reset-email')
            ]
        );
    }

    /**
     * Find token password reset
     */
    public function find($token)
    {
        $passwordReset = PasswordReset::where('token', $token)->first();
        if (!$passwordReset) {
            return response()->json(
                [
                    __('user.invalid-token')
                ],
                404
            );
        }
        if (Carbon::parse($passwordReset->updated_at)->addMinutes(720)->isPast()) {
            $passwordReset->delete();

            return response()->json(
                [
                    __('user.invalid-token')
                ],
                404
            );
        }

        return response()->json($passwordReset);
        //        return redirect()->route('reset')->with($passwordReset);
        //        return $this->view($passwordReset);

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
            return response()->json(
                [
                    __('user.invalid-token')
                ],
                404
            );
        }
        $user = User::where('email', $passwordReset->email)->first();
        if (!$user) {
            return response()->json(
                [
                    __('user.invalid-email')
                ],
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

        return response()->json($user);
    }

    public function changePassword(Request $request)
    {
        $credentials = request(['email', 'password']);
        $credentials['active'] = 1;
        $credentials['deleted_at'] = null;

        if (!Auth::attempt($credentials)) {
            return response()->json(
                [
                    __('user.unauthorized')
                ],
                401
            );
        }

        $user = User::where('email', $request->input('email'))->first();
        $user->password = bcrypt($request->new_password);
        $user->save();

        return response()->json($user);
    }

}
