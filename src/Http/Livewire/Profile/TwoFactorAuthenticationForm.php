<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Http\Livewire\Profile;

use BaconQrCode\Renderer\Color\Rgb;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\Fill;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Contracts\Auth\Authenticatable as User;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Crypt;
use Livewire\Component;
use Rawilk\LaravelBase\Actions\TwoFactor\DeleteAuthenticatorAppAction;
use Rawilk\LaravelBase\Actions\TwoFactor\EnableTwoFactorAuthenticationAction;
use Rawilk\LaravelBase\Actions\TwoFactor\UpdateAuthenticatorAppAction;
use Rawilk\LaravelBase\Contracts\Auth\TwoFactorAuthenticationProvider;
use Rawilk\LaravelBase\Contracts\Models\AuthenticatorApp;
use Rawilk\LaravelBase\Features;
use Rawilk\LaravelBase\Http\Livewire\Concerns\ConfirmsPasswords;
use Rawilk\LaravelBase\LaravelBase;

/**
 * @property-read bool $mustConfirmPassword
 * @property-read \Illuminate\Database\Eloquent\Collection $rows
 */
class TwoFactorAuthenticationForm extends Component
{
    use AuthorizesRequests;
    use ConfirmsPasswords;

    /*
     * This is the maximum amount of apps we will allow the user to register to their account.
     * Set to null for unlimited.
     */
    protected ?int $maxAppsAllowed = null;

    public User $user;

    public bool $showingEnable = false;

    public ?string $twoFactorSecret = null;

    public bool $showEnableInstructions = true;

    public string $confirmationCode = '';

    // For editing an app's name
    public bool $showEdit = false;

    public ?AuthenticatorApp $editing = null;

    public array $editState = [];

    // For deleting an app
    public bool $showDelete = false;

    public ?AuthenticatorApp $deleting = null;

    public function getMustConfirmPasswordProperty(): bool
    {
        return Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword');
    }

    public function getRowsQueryProperty()
    {
        return app(AuthenticatorApp::class)::query()
            ->where('user_id', $this->user->getAuthIdentifier())
            ->orderBy('name');
    }

    public function getRowsProperty()
    {
        return $this->rowsQuery->get([
            'id',
            'name',
            'created_at',
            'last_used_at',
        ]);
    }

    public function enableTwoFactorAuthentication(EnableTwoFactorAuthenticationAction $enabler): void
    {
        if (! Features::canManageTwoFactorAuthentication()) {
            return;
        }

        if ($this->mustConfirmPassword) {
            $this->ensurePasswordIsConfirmed();
        }

        $this->resetErrorBag();

        if (! $this->canAddMoreApps()) {
            $this->showingEnable = false;
            $this->notify(__('laravel-base::2fa.authenticator.alerts.max_reached'), 'error');

            return;
        }

        $enabler($this->user, Crypt::decrypt($this->twoFactorSecret), $this->confirmationCode);

        $this->emit('two-factor-updated');

        $this->twoFactorSecret = null;
        $this->confirmationCode = '';
        $this->showingEnable = false;

        $this->notify(__('laravel-base::2fa.authenticator.alerts.enabled'));
    }

    public function showEnable(TwoFactorAuthenticationProvider $provider): void
    {
        if ($this->mustConfirmPassword) {
            $this->ensurePasswordIsConfirmed();
        }

        $this->twoFactorSecret = Crypt::encrypt($provider->generateSecretKey());

        $this->showEnableInstructions = true;
        $this->confirmationCode = '';
        $this->showingEnable = true;
    }

    public function editApp($id): void
    {
        $this->editing = app(AuthenticatorApp::class)::findOrFail($id, ['id', 'name', 'created_at', 'last_used_at']);
        $this->editState = ['name' => $this->editing->name];
        $this->showEdit = true;
    }

    public function saveApp(UpdateAuthenticatorAppAction $updater): void
    {
        if (! $this->editing) {
            return;
        }

        $this->authorize('edit', $this->editing);

        $updater($this->editing, $this->editState);

        $this->notify(__('laravel-base::2fa.authenticator.alerts.app_updated'));

        $this->editing = null;
        $this->showEdit = false;
    }

    public function confirmDelete($id): void
    {
        if ($this->mustConfirmPassword) {
            $this->ensurePasswordIsConfirmed();
        }

        $this->deleting = app(AuthenticatorApp::class)::findOrFail($id, ['id', 'name']);
        $this->showDelete = true;
    }

    public function deleteApp(DeleteAuthenticatorAppAction $deleter): void
    {
        if ($this->mustConfirmPassword) {
            $this->ensurePasswordIsConfirmed();
        }

        if (! $this->deleting) {
            return;
        }

        $this->authorize('delete', $this->deleting);

        $deleter($this->deleting);

        $this->notify(__('laravel-base::2fa.authenticator.alerts.app_deleted'));

        $this->editing = null;
        $this->deleting = null;
        $this->showDelete = false;

        $this->emit('two-factor-updated');
    }

    public function qrCodeSvg(): ?string
    {
        if (! $url = $this->qrCodeUrl()) {
            return null;
        }

        $svg = (new Writer(
            new ImageRenderer(
                new RendererStyle(192, 0, null, null, Fill::uniformColor(new Rgb(255, 255, 255), new Rgb(45, 55, 72))),
                new SvgImageBackEnd
            )
        ))->writeString($url);

        return trim(substr($svg, strpos($svg, PHP_EOL) + 1));
    }

    private function qrCodeUrl(): ?string
    {
        if (! $this->twoFactorSecret) {
            return null;
        }

        return app(TwoFactorAuthenticationProvider::class)->qrCodeUrl(
            appName(),
            $this->user->{LaravelBase::username()},
            Crypt::decrypt($this->twoFactorSecret),
        );
    }

    public function canAddMoreApps(): bool
    {
        if (! Features::canManageTwoFactorAuthentication()) {
            return false;
        }

        if (is_null($this->maxAppsAllowed)) {
            return true;
        }

        return $this->rowsQuery->clone()->count() < $this->maxAppsAllowed;
    }

    public function boot(): void
    {
        $max = config('laravel-base.authenticator_apps.max_per_user', 3);

        $this->maxAppsAllowed = is_null($max) ? null : (int) $max;
    }

    public function render(): View
    {
        return view('livewire.profile.two-factor-authentication-form', [
            'apps' => $this->rows,
        ]);
    }
}
