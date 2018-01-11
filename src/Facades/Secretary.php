<?php

namespace Dymantic\Secretary\Facades;

use Illuminate\Support\Facades\Facade;

class Secretary extends Facade {

    protected static function getFacadeAccessor()
    {
        return \Dymantic\Secretary\Secretary::class;
    }
}