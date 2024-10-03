<?php

use DigiFactory\FilamentWildcardLogin\Http\Controllers\WildcardLoginController;
use Filament\Facades\Filament;
use Illuminate\Routing\Middleware\ValidateSignature;
use Illuminate\Routing\RouteRegistrar;
use Illuminate\Support\Facades\Route;

Route::name('filament.')
    ->group(function () {
        foreach (Filament::getPanels() as $panel) {
            /** @var Panel $panel */
            $panelId = $panel->getId();
            $hasTenancy = $panel->hasTenancy();
            $tenantRoutePrefix = $panel->getTenantRoutePrefix();
            $tenantDomain = $panel->getTenantDomain();
            $tenantSlugAttribute = $panel->getTenantSlugAttribute();
            $domains = $panel->getDomains();

            foreach ((empty($domains) ? [null] : $domains) as $domain) {
                Route::domain($domain)
                    ->middleware($panel->getMiddleware())
                    ->name("{$panelId}." . ((filled($domain) && (count($domains) > 1)) ? "{$domain}." : ''))
                    ->prefix($panel->getPath())
                    ->group(function () use ($hasTenancy, $tenantDomain, $tenantRoutePrefix, $tenantSlugAttribute) {
                        if ($hasTenancy) {
                            $routeGroup = app(RouteRegistrar::class);

                            if (filled($tenantDomain)) {
                                $routeGroup->domain($tenantDomain);
                            } else {
                                $routeGroup->prefix(
                                    (
                                        filled($tenantRoutePrefix) ?
                                        "{$tenantRoutePrefix}/" :
                                        ''
                                    ) . '{tenant' . (
                                        filled($tenantSlugAttribute) ?
                                        ":{$tenantSlugAttribute}" :
                                        ''
                                    ) . '}',
                                );
                            }

                            $routeGroup
                                ->group(function (): void {
                                    Route::get('filament-wildcard-login', WildcardLoginController::class)
                                        ->name('filament-wildcard-login')
                                        ->middleware(ValidateSignature::class);
                                });
                        } else {
                            Route::get('filament-wildcard-login', WildcardLoginController::class)
                                ->name('filament-wildcard-login')
                                ->middleware(ValidateSignature::class);
                        }
                    });
            }
        }
    });
