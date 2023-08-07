<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyEmail
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user_all = User::all();

        $user = $user_all->where('email', '=',$request->email)->first();
        if(!$user){
            return response()->json([
                'message' => 'Este usuario no existe'
            ], 404);
        }
        if(!$user->email_verified_at){
            return response()->json([
                'message' => 'Este usuario no esta confirmado'
            ], 401);
        }
        return $next($request);
    }
}
