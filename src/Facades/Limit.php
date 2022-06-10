<?php

namespace Williamcruzme\Limits\Facades;

use Illuminate\Support\Facades\Facade;
use Williamcruzme\Limits\Factory;

/**
 * @method static void register($key, $class)
 * @method static array limits()
 * @method static mixed resolve(\Williamcruzme\Limits\Repository $repository)
 * @method static void defaultLimit(callable $defaultLimitCallback)
 * @method static mixed resolveDefaultLimit($limit, $key)
 */
class Limit extends Facade
{
    protected static function getFacadeAccessor()
    {
        return Factory::class;
    }
}
