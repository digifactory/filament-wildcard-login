<?php

namespace DigiFactory\FilamentWildcardLogin\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \DigiFactory\FilamentWildcardLogin\FilamentWildcardLogin
 */
class FilamentWildcardLogin extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \DigiFactory\FilamentWildcardLogin\FilamentWildcardLogin::class;
    }
}
