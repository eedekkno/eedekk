<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\SetCurrentTeamRequest;
use App\Models\Team;
use Illuminate\Http\RedirectResponse;

class UserSetCurrentTeamController extends Controller
{
    public function __invoke(SetCurrentTeamRequest $request, Team $team): RedirectResponse
    {
        $request->user()->team()->associate($team)->save();

        return back();
    }
}
