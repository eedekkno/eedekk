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
        /** @var \App\Models\User|null $user */
        $user = $request->user();

        if ($user) {
            /** @var \App\Models\Team|null $team */
            $team = $user->team;

            if ($user->current_team_id !== null) {
                /** @var \App\Models\Team|null $team */
                $teams = $user->teams;

                abort_unless($team && $teams->contains($team), 403);
            }

            if ($team) {
                setPermissionsTeamId($team->id);
            }
        }

        return $next($request);
    }
}
