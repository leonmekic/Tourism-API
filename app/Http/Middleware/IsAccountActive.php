<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
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
        $user = auth()->guard('api')->user();

        $expirationDate = Carbon::parse($user->created_at)->addDays(3);
        $now = Carbon::now();

        if ($user->active == 0 && $now > $expirationDate) {
            throw new \Exception('Account is inactive');
        }

        return $next($request);
    }
}
