<?php

namespace DigiFactory\FilamentWildcardLogin;

use DigiFactory\FilamentWildcardLogin\Filament\Pages\Login;
use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Support\Concerns\EvaluatesClosures;

class FilamentWildcardLoginPlugin implements Plugin
{
    use EvaluatesClosures;

    protected Closure | bool $enabled = true;

    protected Closure | bool $loginDirectlyWithoutSendingEmail = false;

    /**
     * @var array<int, string>
     */
    protected array $domains = [];

    public string $modelColumn = 'email';

    public string $modelClass = 'App\\Models\\User';

    public function getId(): string
    {
        return 'filament-wildcard-login';
    }

    public function register(Panel $panel): void
    {
        $panel->login(Login::class);
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }

    public function enabled(Closure | bool $value = true): static
    {
        $this->enabled = $value;

        return $this;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function loginDirectlyWithoutSendingEmail(Closure | bool $value = true): static
    {
        $this->loginDirectlyWithoutSendingEmail = $value;

        return $this;
    }

    public function shouldLoginDirectlyWithoutSendingEmail(): bool
    {
        return $this->loginDirectlyWithoutSendingEmail;
    }

    /**
     * @param  array<int, string>  $domains
     * @return $this
     */
    public function domains(array $domains): static
    {
        $this->domains = array_map(fn ($domain) => str_starts_with($domain, '@') ? $domain : '@' . $domain, $domains);

        return $this;
    }

    public function getDomains(): array
    {
        return $this->domains;
    }

    public function model(string $modelClass, string $modelColumn): static
    {
        $this->modelClass = $modelClass;
        $this->modelColumn = $modelColumn;

        return $this;
    }

    public function getModelClass(): string
    {
        return $this->modelClass;
    }

    public function getModelColumn(): string
    {
        return $this->modelColumn;
    }
}
