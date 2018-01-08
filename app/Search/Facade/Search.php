<?php

namespace App\Search\Facade;

use Illuminate\Support\Facades\Facade;

/**
 * Class Search.
 *
 * @author Rohit Modi <rohitpmodi@gmail.com>
 */
class Search extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'search';
    }
}
