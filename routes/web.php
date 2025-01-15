<?php

declare(strict_types=1);

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TeamInviteController;
use App\Http\Controllers\TeamMemberController;
use App\Http\Controllers\UserSetCurrentTeamController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('welcome'));

Route::get('dashboard', fn () => view('dashboard'))->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function (): void {
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::patch('teams/{team}/set', UserSetCurrentTeamController::class)->name('user.setTeam');
    Route::get('team', [TeamController::class, 'edit'])->name('team.edit');
    Route::get('team/create', [TeamController::class, 'create'])->name('team.create');
    Route::post('team', [TeamController::class, 'store'])->name('team.store');
    Route::patch('team/{team}', [TeamController::class, 'update'])->name('team.update');
    Route::delete('team/{team}/leave', [TeamController::class, 'leave'])->name('team.leave');
    Route::patch('team/{team}/users/{user}', [TeamMemberController::class, 'update'])->name('team.members.update');
    Route::delete('team/{team}/users/{user}', [TeamMemberController::class, 'destroy'])->name('team.members.destroy');
    Route::post('team/{team}/invites', [TeamInviteController::class, 'store'])->name('team.invites.store');
    Route::delete('team/{team}/invites/{teamInvite}', [TeamInviteController::class, 'destroy'])->name('team.invites.destroy');
    Route::get('team/invites/accept', [TeamInviteController::class, 'accept'])->name('team.invites.accept')->middleware('signed');
});

require __DIR__.'/auth.php';

Route::get('coverage', fn () => response()->file(public_path('coverage/index.html')));
