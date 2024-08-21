<?php

use DigiFactory\FilamentWildcardLogin\Http\Controllers\WildcardLoginController;
use Illuminate\Routing\Middleware\ValidateSignature;
use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::get('filament-wildcard-login', WildcardLoginController::class)
        ->name('filament-wildcard-login')
        ->middleware(ValidateSignature::class);
});
