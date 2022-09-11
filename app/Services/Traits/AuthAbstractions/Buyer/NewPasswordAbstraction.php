<?php

namespace App\Services\Traits\AuthAbstractions\Buyer;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

use App\Services\Traits\Utilities\PassHashVerifyService;

trait NewPasswordAbstraction
{
    use PassHashVerifyService;
    /**
     * Handle an incoming new password request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function ResetPassword(Request $request): string
    {
        
        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($buyer) use ($request) 
            {
                $buyer->forceFill([
                    'password' => $this->CustomHashPassword($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($buyer));
            }
        );

        if ($status != Password::PASSWORD_RESET) {
            throw ValidationException::withMessages([
                'email' => [__($status)],
            ]);
        }

        //return response()->json(['status' => __($status)]);
        return __($status);
    }
}
