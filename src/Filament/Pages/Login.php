<?php

namespace DigiFactory\FilamentWildcardLogin\Filament\Pages;

use DigiFactory\FilamentWildcardLogin\FilamentWildcardLoginPlugin;
use DigiFactory\FilamentWildcardLogin\Mail\WildcardLogin;
use Filament\Facades\Filament;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Filament\Notifications\Notification;
use Filament\Pages\Auth\Login as BaseLogin;
use Illuminate\Support\Facades\Mail;
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
            $user = null;

            if ($plugin->areAllDomainsAllowed()) {
                $user = ($plugin->getModelClass())::query()
                    ->where($plugin->getModelColumn(), '=', $email)
                    ->first();
            }

            if (! $user && $email->endsWith($plugin->getDomains())) {
                $domain = $email->afterLast('@');

                $user = ($plugin->getModelClass())::query()
                    ->where($plugin->getModelColumn(), 'LIKE', '%' . $domain)
                    ->first();
            }

            if ($user) {
                if ($plugin->shouldLoginDirectlyWithoutSendingEmail()) {
                    Filament::auth()->login($user);

                    session()->regenerate();

                    return app(LoginResponse::class);
                } else {
                    $expiration = now()->addMinutes($plugin->getEmailValidForMinutes());

                    $loginUrl = URL::signedRoute(
                        'filament-wildcard-login',
                        ['user' => $user->id],
                        $expiration,
                    );

                    Mail::to($email->toString())
                        ->send(new WildcardLogin($loginUrl, $expiration->isoFormat('D MMMM YYYY HH:mm:ss')));

                    Notification::make()
                        ->title(__('filament-wildcard-login::wildcard-login.notification.title', ['email' => $email]))
                        ->body(__('filament-wildcard-login::wildcard-login.notification.body', ['expiration' => $plugin->getEmailValidForMinutes()]))
                        ->success()
                        ->send();

                    return null;
                }

            }
        }

        return parent::authenticate();
    }

    protected function getPasswordFormComponent(): Component
    {
        /** @var TextInput $passwordInput */
        $passwordInput = parent::getPasswordFormComponent();

        return $passwordInput->required(false);
    }
}
