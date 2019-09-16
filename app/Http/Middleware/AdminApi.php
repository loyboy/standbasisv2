<?php

namespace App\Http\Middleware;

use Closure;
use App\User;

class AdminApi
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = $request->get('api_token');
        $user = User::where('api_token', $token)->first();
        if ($user === null){
            return response()->json(['error' => "Incorrect API token"]);
        }
        return $next($request);
    }
}
