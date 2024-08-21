<?php

namespace DigiFactory\FilamentWildcardLogin\Filament\Pages;

use DigiFactory\FilamentWildcardLogin\FilamentWildcardLoginPlugin;
use DigiFactory\FilamentWildcardLogin\Notifications\WildcardLogin;
use Filament\Facades\Filament;
use Filament\Forms\Components\Component;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Filament\Notifications\Notification;
use Filament\Pages\Auth\Login as BaseLogin;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class Login extends BaseLogin
{
    public function authenticate(): ?LoginResponse
    {
        $data = $this->form->getState();

        $plugin = FilamentWildcardLoginPlugin::get();

        if (isset($data['email']) && $plugin->isEnabled()) {
            $email = Str::of($data['email']);

            if ($email->endsWith($plugin->getDomains())) {
                $domain = $email->afterLast('@');

                $user = ($plugin->getModelClass())::query()
                    ->where($plugin->getModelColumn(), 'LIKE', '%'.$domain)
                    ->first();

                if ($user) {
                    if ($plugin->shouldLoginDirectlyWithoutSendingEmail()) {
                        Filament::auth()->login($user);

                        session()->regenerate();

                        return app(LoginResponse::class);
                    } else {
                        $loginUrl = URL::signedRoute(
                            'filament-wildcard-login',
                            ['user' => $user->id],
                            now()->addMinutes(5),
                        );

                        $user->notify(new WildcardLogin($loginUrl));

                        Notification::make()
                            ->title(__('filament-wildcard-login::wildcard-login.notification.title', ['email' => $email]))
                            ->body(__('filament-wildcard-login::wildcard-login.notification.body', ['expiration' => 5]))
                            ->success()
                            ->send();

                        return null;
                    }
                }
            }
        }

        return parent::authenticate();
    }

    protected function getPasswordFormComponent(): Component
    {
        return parent::getPasswordFormComponent()
            ->required(false);
    }
}
