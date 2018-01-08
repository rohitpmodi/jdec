<?php

namespace App\Providers;

use App\Feeder\Feeder;
use Illuminate\Support\ServiceProvider;

/**
 * Class FeederServiceProvider.
 *
 * @author Rohit Modi <rohitpmodi@gmail.com>
 */
class FeederServiceProvider extends ServiceProvider
{
    /**
     * Register.
     */
    public function register()
    {
        $this->app->bind('feeder', 'App\Feeder\Feeder');
    }
}
