<?php

namespace DigiFactory\FilamentWildcardLogin\Tests\Browser;

use BladeUI\Heroicons\BladeHeroiconsServiceProvider;
use BladeUI\Icons\BladeIconsServiceProvider;
use DigiFactory\FilamentWildcardLogin\Filament\Pages\Login;
use DigiFactory\FilamentWildcardLogin\FilamentWildcardLoginServiceProvider;
use DigiFactory\FilamentWildcardLogin\Mail\WildcardLogin;
use DigiFactory\FilamentWildcardLogin\Tests\TestCase;
use Filament\Actions\ActionsServiceProvider;
use Filament\FilamentServiceProvider;
use Filament\Forms\FormsServiceProvider;
use Filament\Infolists\InfolistsServiceProvider;
use Filament\Notifications\NotificationsServiceProvider;
use Filament\Support\SupportServiceProvider;
use Filament\Tables\TablesServiceProvider;
use Filament\Widgets\WidgetsServiceProvider;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Livewire\LivewireServiceProvider;
use RyanChandler\BladeCaptureDirective\BladeCaptureDirectiveServiceProvider;
use Workbench\App\Models\User;
use Workbench\App\Providers\Filament\AdminPanelWithWildcardDomainProvider;

class WildcardDomainLoginPageTest extends TestCase
{
    use DatabaseMigrations;

    protected function getPackageProviders($app)
    {
        return [
            ActionsServiceProvider::class,
            BladeCaptureDirectiveServiceProvider::class,
            BladeHeroiconsServiceProvider::class,
            BladeIconsServiceProvider::class,
            FilamentServiceProvider::class,
            FormsServiceProvider::class,
            InfolistsServiceProvider::class,
            LivewireServiceProvider::class,
            NotificationsServiceProvider::class,
            SupportServiceProvider::class,
            TablesServiceProvider::class,
            WidgetsServiceProvider::class,
            FilamentWildcardLoginServiceProvider::class,
            AdminPanelWithWildcardDomainProvider::class,
        ];
    }

    /**
     * @test
     */
    public function it_can_see_login_page()
    {
        $this->get('/admin/login')
            ->assertOk();
    }

    /**
     * @test
     */
    public function it_can_see_password_field_is_not_required()
    {
        $component = Livewire::test(Login::class);

        $this->assertStringContainsString(
            '<input    class="fi-input block w-full border-none py-1.5 text-base text-gray-950 transition duration-75 placeholder:text-gray-400 focus:ring-0 disabled:text-gray-500 disabled:[-webkit-text-fill-color:theme(colors.gray.500)] disabled:placeholder:[-webkit-text-fill-color:theme(colors.gray.400)] dark:text-white dark:placeholder:text-gray-500 dark:disabled:text-gray-400 dark:disabled:[-webkit-text-fill-color:theme(colors.gray.400)] dark:disabled:placeholder:[-webkit-text-fill-color:theme(colors.gray.500)] sm:text-sm sm:leading-6 bg-white/0 ps-3 pe-3 [&amp;::-ms-reveal]:hidden" autocomplete="current-password" id="data.password" wire:model="data.password" x-bind:type="isPasswordRevealed ? \'text\' : \'password\'" tabindex="2"/>',
            Str::of($component->html())->replace(PHP_EOL, '')->toString(),
        );
    }

    /**
     * @test
     */
    public function it_can_see_error_when_using_no_wildcard_email()
    {
        $component = Livewire::test(Login::class);
        $component->set('data.email', 'foo@bar.example');
        $component->call('authenticate');
        $component->assertSee('These credentials do not match our records.');
    }

    /**
     * @test
     */
    public function it_can_see_notification_when_using_wildcard_email()
    {
        User::create([
            'name' => 'DigiFactory',
            'email' => 'helpdesk@digifactory.nl',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'name' => 'DigiFactory',
            'email' => 'mark@digifactory.nl',
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
    }
}
