<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RolesMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();
        if (empty($user)) {
            return redirect('login');
        }

        if($user->isAdmin()) {
            return $next($request);
        }

        foreach($roles as $role) {
            // Check if user has the role This check will depend on how your roles are set up
            if (in_array($role, $user->roles))
                return $next($request);
        }

        return abort(403);
    }
}
