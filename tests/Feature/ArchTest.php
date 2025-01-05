<?php

declare(strict_types=1);

arch('Do not leave debug statements')
    ->expect(['dd', 'dump', 'var_dump'])
    ->not->toBeUsed();

arch('Do not use env helper in code')
    ->expect(['env'])
    ->not->toBeUsed();

arch('Action classes should be invokable')
    ->expect('App\Actions')
    ->toBeInvokable();

arch('Job classes should have handle method')
    ->expect('App\Jobs')
    ->toHaveMethod('handle');

arch('Controller classes should have proper suffix')
    ->expect('App\Controllers')
    ->toHaveSuffix('Controller');

arch('app')
    ->expect('App\Enums')
    ->toBeEnums();

arch()
    ->expect('App\Models')
    ->toBeClasses()
    ->toExtend(\Illuminate\Database\Eloquent\Model::class)
    ->ignoring(\App\Models\User::class);

arch()
    ->expect('App\Http')
    ->toOnlyBeUsedIn('App\Http');

arch()->preset()->php();
arch()->preset()->security()->ignoring('md5');
