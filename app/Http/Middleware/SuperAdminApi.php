<?php

namespace App\Http\Middleware;

use Closure;
use App\User;

class SuperAdminApi
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
        //Use the Number '9' to signifiy that this is a Standbasis Admin
        $user = User::where('api_token', $token)->where('_type', 9)->first();
        if ($user === null){
            return response()->json(['error' => "Incorrect API token for Standbasis Admin"]);
        }
        return $next($request);
    }
}
