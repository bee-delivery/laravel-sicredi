<?php

namespace Beedelivery\Sicredi\Utils\Facades;

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