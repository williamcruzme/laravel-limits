<?php

namespace Williamcruzme\Limits;

use Illuminate\Support\ServiceProvider;
use Williamcruzme\Limits\Facades\Limit;

class LimitServiceProvider extends ServiceProvider
{
    protected $limits = [];

    public function registerLimits()
    {
        foreach ($this->limits() as $key => $class) {
            Limit::register($key, $class);
        }
    }

    public function limits()
    {
        return $this->limits;
    }
}
