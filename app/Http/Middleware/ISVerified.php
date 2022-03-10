<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class ISVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user=User::where('email',$request->username)->first();
        if (! $user ||
            ($user instanceof MustVerifyEmail &&
            ! $user->hasVerifiedEmail())) {
            // return $request->expectsJson()
            //         ? abort(403, 'Your email address is not verified.')
            //         : Redirect::guest(URL::route($redirectToRoute ?: 'verification.notice'));
            return response()->json(
                [
                    'status' => false,
                    'message' => "Sorry, you are not authorized or do not have the permission",
                    'data' => null
                ],
                403
            );
        }

        return $next($request);
    }
}
