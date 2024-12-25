<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TeamPermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($user = $request->user()) {
            if (! is_null($user->current_team_id)) {
                abort_unless($user->teams->contains($user->team), 403);
            }

            setPermissionsTeamId($user->team->id);
        }

        return $next($request);
    }
}
