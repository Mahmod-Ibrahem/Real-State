<?php

namespace App\Services;

use App\Events\ImpersonationEnded;
use App\Events\ImpersonationStarted;
use App\Exceptions\CannotImpersonate;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

final class ImpersonationService
{
    public const SESSION_KEY = 'impersonator_id';

    public function start(User $admin, User $user): void
    {
        $this->ensureCanImpersonate($admin, $user);

        session()->put(self::SESSION_KEY, $admin->id);
        Auth::guard('web')->login($user);

        ImpersonationStarted::dispatch($admin, $user);
    }

    public function stop(): User
    {
        $admin = $this->impersonator();
        session()->forget(self::SESSION_KEY);

        if ($admin === null) {
            throw CannotImpersonate::notImpersonating();
        }

        $user = Auth::guard('web')->user();
        Auth::guard('web')->login($admin);

        ImpersonationEnded::dispatch($admin, $user);

        return $admin;
    }

    public function isImpersonating(): bool
    {
        return session()->has(self::SESSION_KEY);
    }

    public function impersonator(): ?User
    {
        return $this->isImpersonating() ? User::find(session()->get(self::SESSION_KEY)) : null;
    }

    private function ensureCanImpersonate(User $admin, User $user): void
    {
        match (true) {
            $this->isImpersonating() => throw CannotImpersonate::alreadyImpersonating(),
            $user->is($admin) => throw CannotImpersonate::self(),
            $user->isStaff() => throw CannotImpersonate::protectedAccount(),
            ! $user->canBeImpersonated() => throw CannotImpersonate::inactive(),
            default => null,
        };
    }
}
