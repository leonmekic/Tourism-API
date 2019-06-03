<?php

namespace App\Http\Middleware;

use Closure;

class IsAccountActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $expirationDate = auth()->guard('api')->user()->created_at->add(3, 'day');

        if (auth()->guard('api')->user()->active == 0) {
            if (date(now()) > $expirationDate) {
                return redirect('home');
            }

            return $next($request);
        }

        return $next($request);
    }
}
