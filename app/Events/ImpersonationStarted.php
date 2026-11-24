<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class ImpersonationStarted
{
    use Dispatchable, SerializesModels;

    public function __construct(public User $impersonator, public User $user)
    {
    }
}
