<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\Factory as Auth;

class EnsureEmailIsVerified
{

    
      /**
     * The authentication factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */


    
    public function handle($request, Closure $next, $guard = null)
    {
        
        return $next($request);
        if (! $request->user($guard)) {
            if($request->expectsJson()) {
                return response()->json([
                    'message' => 'You do not have permission to access this feature.',
                    'errors' => [
                        'main' => ['The access token is either missing or incorrect.']
                    ]
                ], 401);
            } else {
                return redirect(route('login'));
            }
        } else if
            ($request->user($guard) instanceof MustVerifyEmail &&
            ! $request->user($guard)->hasVerifiedEmail()) {

            if($request->expectsJson()) {
                return response()->json([
                    'message' => 'You do not have permission to access this feature.',
                    'errors' => [
                        'main' => ['Your email address is not verified.']
                    ]
                ], 403);
            } else {
                return redirect(route('verification.notice'));
                return redirect(route('verification.notice'));
            }
        }

        $this->auth->shouldUse($guard);

        return $next($request);
    }
}


