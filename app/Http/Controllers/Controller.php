<?php

namespace App\Http\Controllers;

use App\Traits\handleDbErrors;

abstract class Controller
{
    use handleDbErrors;
}
