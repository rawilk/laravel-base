<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Http\Livewire\Profile;

use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Jenssegers\Agent\Agent;
use Livewire\Component;

/**
 * @property-read \Illuminate\Support\Collection $sessions
 */
class LogoutOtherBrowserSessionsForm extends Component
{
    public bool $confirmingLogout = false;

    /*
     * The user's current password.
     */
    public string $password = '';

    public function getSessionsProperty(): Collection
    {
        if (Config::get('session.driver') !== 'database') {
            return collect();
        }

        return collect(
            DB::connection(Config::get('session.connection'))->table(Config::get('session.table', 'sessions'))
                ->where('user_id', Auth::user()->getAuthIdentifier())
                ->orderBy('last_activity', 'desc')
                ->get()
        )->map(function ($session) {
            return (object) [
                'agent' => $this->createAgent($session),
                'ip_address' => $session->ip_address,
                'is_current_device' => $session->id === request()->session()->getId(),
                'last_active' => Carbon::createFromTimestamp($session->last_activity)->diffForHumans(),
            ];
        });
    }

    /**
     * Confirm that the user would like to log out from other browser sessions.
     */
    public function confirmLogout(): void
    {
        $this->resetErrorBag();

        $this->password = '';

        $this->dispatchBrowserEvent('confirming-logout-other-browser-sessions');

        $this->confirmingLogout = true;
    }

    public function logoutOtherBrowserSessions(StatefulGuard $guard)
    {
        $this->resetErrorBag();

        if (! Hash::check($this->password, Auth::user()->password)) {
            throw ValidationException::withMessages([
                'password' => __('This password does not match our records.'),
            ]);
        }

        $guard->logoutOtherDevices($this->password);

        $this->deleteOtherSessionRecords();

        $this->confirmingLogout = false;

        $this->emitSelf('logged_out');
    }

    public function render(): View
    {
        return view('livewire.profile.logout-other-browser-sessions-form');
    }

    /**
     * Delete the other browser session records from storage.
     */
    protected function deleteOtherSessionRecords(): void
    {
        if (Config::get('session.driver') !== 'database') {
            return;
        }

        DB::connection(Config::get('session.connection'))->table(Config::get('session.table', 'sessions'))
            ->where('user_id', Auth::user()->getAuthIdentifier())
            ->where('id', '!=', request()->session()->getId())
            ->delete();
    }

    protected function createAgent($session)
    {
        return tap(new Agent, function (Agent $agent) use ($session) {
            $agent->setUserAgent($session->user_agent);
        });
    }
}
