<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Http\Livewire\Profile;

use Illuminate\Contracts\Auth\Authenticatable as User;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Arr;
use Livewire\Component;
use Rawilk\LaravelBase\Actions\Webauthn\DeleteWebauthnKeyAction;
use Rawilk\LaravelBase\Actions\Webauthn\UpdateWebauthnKeyAction;
use Rawilk\LaravelBase\Features;
use Rawilk\LaravelBase\Http\Livewire\Concerns\ConfirmsPasswords;
use Rawilk\Webauthn\Actions\PrepareKeyCreationData;
use Rawilk\Webauthn\Actions\RegisterNewKeyAction;
use Rawilk\Webauthn\Contracts\WebauthnKey;
use Rawilk\Webauthn\Exceptions\WebauthnRegisterException;
use Rawilk\Webauthn\Facades\Webauthn;

/**
 * @property-read bool $mustConfirmPassword
 * @property-read \Illuminate\Database\Eloquent\Collection $rows
 */
abstract class RegisterWebauthnKeyForm extends Component
{
    use AuthorizesRequests;
    use ConfirmsPasswords;

    /*
     * This is the maximum amount of keys we will allow the user to register to their account.
     * Set to null for unlimited.
     */
    protected ?int $maxKeysAllowed = null;

    public string $newKeyName = '';

    public bool $showInstructions = true;

    public bool $showAddKey = false;

    protected string $attachmentType = 'cross-platform';

    public User $user;

    public ?string $publicKey = null;

    protected string $viewName = '';

    // For editing a key's name
    public bool $showEdit = false;

    public ?WebauthnKey $editing = null;

    public array $editState = [];

    // For deleting a key
    public bool $showDelete = false;

    public ?WebauthnKey $deleting = null;

    public function getMustConfirmPasswordProperty(): bool
    {
        return Features::optionEnabled(Features::webauthn(), 'confirmPassword');
    }

    public function getRowsQueryProperty()
    {
        return app(WebauthnKey::class)::query()
            ->where('user_id', $this->user->getAuthIdentifier())
            ->where('attachment_type', $this->attachmentType)
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

    public function canAddMoreKeys(): bool
    {
        if (! Webauthn::webauthnIsEnabled() || ! Features::canManageWebauthnAuthentication()) {
            return false;
        }

        if (is_null($this->maxKeysAllowed)) {
            return true;
        }

        return $this->rowsQuery->clone()->count() < $this->maxKeysAllowed;
    }

    public function editKey($id): void
    {
        $this->editing = app(WebauthnKey::class)::findOrFail($id, ['id', 'name', 'created_at', 'last_used_at']);
        $this->editState = ['name' => $this->editing->name];
        $this->showEdit = true;
    }

    public function saveKey(UpdateWebauthnKeyAction $updater): void
    {
        if (! $this->editing) {
            return;
        }

        $this->authorize('edit', $this->editing);

        $updater($this->editing, $this->editState);

        $this->notify(__('base::webauthn.alerts.updated'));

        $this->editing = null;
        $this->showEdit = false;
    }

    public function confirmDelete($id): void
    {
        if ($this->mustConfirmPassword) {
            $this->ensurePasswordIsConfirmed();
        }

        $this->deleting = app(WebauthnKey::class)::findOrFail($id, ['id', 'name', 'created_at']);
        $this->showDelete = true;
    }

    public function deleteKey(DeleteWebauthnKeyAction $deleter): void
    {
        if ($this->mustConfirmPassword) {
            $this->ensurePasswordIsConfirmed();
        }

        if (! $this->deleting) {
            return;
        }

        $this->authorize('delete', $this->deleting);

        $deleter($this->deleting);

        $this->notify(__('base::webauthn.alerts.deleted'));

        $this->editing = null;
        $this->deleting = null;
        $this->publicKey = null;
        $this->showDelete = false;

        $this->emit('two-factor-updated');
    }

    public function registerKey($data): void
    {
        if ($this->mustConfirmPassword) {
            $this->ensurePasswordIsConfirmed();
        }

        $this->resetErrorBag();

        if (! $this->canAddMoreKeys()) {
            $this->showAddKey = false;
            $this->notify(__('base::webauthn.alerts.max_reached'), 'error');

            return;
        }

        $this->validate([
            'newKeyName' => ['required', 'string', 'max:255'],
        ]);

        try {
            config(['webauthn.attachment_mode' => $this->attachmentType]);

            app(RegisterNewKeyAction::class)(
                $this->user,
                array_merge(Arr::only($data, ['id', 'rawId', 'response', 'type']), ['attachment_type' => $this->attachmentType]),
                $this->newKeyName,
            );
        } catch (WebauthnRegisterException $e) {
            $this->addError('newKeyName', $e->getMessage());

            return;
        }

        $this->publicKey = null;
        $this->showAddKey = false;

        $this->emit('two-factor-updated');
    }

    public function showAddKey(): void
    {
        if ($this->mustConfirmPassword) {
            $this->ensurePasswordIsConfirmed();
        }

        $this->resetErrorBag();

        $this->showInstructions = true;
        $this->showAddKey = true;
    }

    public function render(): View
    {
        config([
            'webauthn.attachment_mode' => $this->attachmentType,
        ]);

        if (! $this->publicKey) {
            $this->publicKey = json_encode(app(PrepareKeyCreationData::class)($this->user, $this->attachmentType));
        }

        return view($this->viewName, [
            'securityKeys' => $this->rows,
        ]);
    }
}
