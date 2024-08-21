<?php

namespace DigiFactory\FilamentWildcardLogin\Http\Controllers;

use DigiFactory\FilamentWildcardLogin\FilamentWildcardLoginPlugin;
use Filament\Facades\Filament;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Illuminate\Http\Request;

class WildcardLoginController
{
    public function __invoke(Request $request)
    {
        $plugin = FilamentWildcardLoginPlugin::get();

        $user = ($plugin->getModelClass())::query()
            ->findOrFail($request->input('user'));

        Filament::auth()->login($user);

        session()->regenerate();

        return app(LoginResponse::class);
    }
}
