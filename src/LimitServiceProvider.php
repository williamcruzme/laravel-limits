<?php

namespace Williamcruzme\Limits;

use Illuminate\Support\ServiceProvider;

class LimitServiceProvider extends ServiceProvider
{
    protected $limits = [];

    public function registerLimits()
    {
        foreach ($this->limits() as $key => $limit) {
            Limit::register($key, $limit);
        }
    }

    public function limits()
    {
        return $this->limits;
    }
}
