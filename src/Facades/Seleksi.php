<?php

namespace Bantenprov\Seleksi\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * The Seleksi facade.
 *
 * @package Bantenprov\Seleksi
 * @author  bantenprov <developer.bantenprov@gmail.com>
 */
class SeleksiFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'seleksi';
    }
}
