<?php

namespace Beedelivery\Sicredi\Facades;

use Illuminate\Support\Facades\Facade;

class Sicredi extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'sicredi';
    }
}