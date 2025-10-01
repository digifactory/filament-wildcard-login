<?php

declare(strict_types=1);

use DigiFactory\FilamentWildcardLogin\Filament\Pages\Login;
use DigiFactory\FilamentWildcardLogin\FilamentWildcardLoginPlugin;
use DigiFactory\FilamentWildcardLogin\Mail\WildcardLogin;
use DigiFactory\FilamentWildcardLogin\Tests\Fixtures\Models\User;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;

beforeEach(function () {
    $this->panel = Filament::getCurrentOrDefaultPanel();
});

it('can see login page', function () {
    $this->panel
        ->plugins([
            FilamentWildcardLoginPlugin::make(),
        ]);

    $this->get('/admin/login')
        ->assertOk();
});

it('can see password field is not required', function () {
    $component = Livewire::test(Login::class);

    $this->assertStringContainsString(
        '<input            class="fi-input fi-revealable" autocomplete="current-password" id="form.password" wire:model="data.password" x-bind:type="isPasswordRevealed ? \'text\' : \'password\'" tabindex="2"        />',
        Str::of($component->html())->replace(PHP_EOL, '')->toString(),
    );
});

it('can see error when using no wildcard email', function () {
    $this->panel
        ->plugins([
            FilamentWildcardLoginPlugin::make(),
        ]);

    $component = Livewire::test(Login::class);
    $component->set('data.email', 'foo@bar.example');
    $component->call('authenticate');
    $component->assertSee('These credentials do not match our records.');
});

it('can see notification when using wildcard email', function () {
    $this->panel
        ->plugins([
            FilamentWildcardLoginPlugin::make()
                ->domains([
                    'digifactory.nl',
                ])
                    ->model(User::class)
                    ->loginDirectlyWithoutSendingEmail(app()->environment('local')),
        ]);

    Filament::setCurrentPanel($this->panel);

    User::create([
        'name' => 'DigiFactory',
        'email' => 'helpdesk@digifactory.nl',
        'password' => Hash::make('password'),
    ]);

    Mail::fake();

    $component = Livewire::test(Login::class);
    $component->set('data.email', 'mark@digifactory.nl');
    $component->call('authenticate');

    $notifications = session()->get('filament.notifications');

    expect($notifications)
        ->toBeArray()
        ->toHaveCount(1);

    $notification = Arr::last($notifications);

    expect($notification)
        ->toBeArray()
        ->body->toBe('This e-mail is valid for 5 minutes.')
        ->title->toBe('Login link sent to mark@digifactory.nl!');

    Mail::assertQueued(WildcardLogin::class, function (WildcardLogin $mail) {
        return expect($mail)
            ->to->toBe([['name' => null, 'address' => 'mark@digifactory.nl']])
            ->envelope()->subject->toBe('Login link Laravel');
    });
    Mail::assertQueuedCount(1);
});
