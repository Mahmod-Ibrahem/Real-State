<?php

namespace App\Listeners;

use App\Events\ImpersonationEnded;
use App\Events\ImpersonationStarted;
use App\Models\User;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;
use Illuminate\Support\Facades\Log;

final class LogImpersonation implements ShouldHandleEventsAfterCommit
{
    public function handle(ImpersonationStarted|ImpersonationEnded $event): void
    {
        $action = $event instanceof ImpersonationStarted ? 'started' : 'stopped';

        Log::info("Admin {$this->describe($event->impersonator)} {$action} impersonating user {$this->describe($event->user)}");
    }

    private function describe(User $user): string
    {
        return "#{$user->id} ({$user->email})";
    }
}
