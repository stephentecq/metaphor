<?php

namespace Mustaard\Metaphor\Facades;

/**
 * Created by PhpStorm.
 * User: stephenmunabo
 * Date: 5/18/17
 * Time: 9:24 AM
 */

use Illuminate\Support\Facades\Facade;

class Metaphor Extends Facade
{
    protected static function getFacadeAccessor()
    {
        return "metaphor";
    }
}