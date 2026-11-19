<?php

namespace App\Traits;

trait handleDbErrors
{
    public function handleDbErrors($e)
    {
       return match ($e->getCode()) {
            1062 => $e->getMessage(),
            "23000" => 'The Given Data Already Exists Try Insert Another One',
            default => 'Something Went Wrong',
        };
    }
}
