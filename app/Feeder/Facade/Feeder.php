<?php

namespace App\Feeder\Facade;

use Illuminate\Support\Facades\Facade;

/**
 * Class Feeder.
 *
 * @author Rohit Modi <rohitpmodi@gmail.com>
 */
class Feeder extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'feeder';
    }
}
