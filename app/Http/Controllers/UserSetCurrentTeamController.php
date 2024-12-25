<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\SetCurrentTeamRequest;
use App\Models\Team;

class UserSetCurrentTeamController extends Controller
{
    public function __invoke(SetCurrentTeamRequest $request, Team $team)
    {
        $request->user()->team()->associate($team)->save();

        return back();
    }
}
