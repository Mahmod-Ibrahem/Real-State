<?php

namespace App\Enums\Concerns;

trait IsPermission
{
    public function middleware(): string
    {
        return 'permission:' . $this->value;
    }
}
