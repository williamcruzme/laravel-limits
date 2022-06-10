<?php

namespace Williamcruzme\Limits\Facades;

use Illuminate\Support\Facades\Facade;
use Williamcruzme\Limits\Factory;
use Williamcruzme\Limits\Repository;

/**
 * @method static self actingAs(\Illuminate\Contracts\Auth\Authenticatable $user)
 * @method static mixed user()
 */
class Limit extends Facade
{
    public static $limits = [];

    public static $defaultLimitCallback;

    protected static function getFacadeAccessor()
    {
        return Factory::class;
    }

    public static function register($key, $class)
    {
        static::$limits[$key] = $class;
    }

    public static function limits()
    {
        return static::$limits;
    }

    public static function resolve(Repository $repository)
    {
        return new static::$limits[$repository->name];
    }

    public static function defaultLimit(callable $defaultLimitCallback)
    {
        static::$defaultLimitCallback = $defaultLimitCallback;
    }

    public static function resolveDefaultLimit($limit, $key)
    {
        if (static::$defaultLimitCallback) {
            return call_user_func(static::$defaultLimitCallback, $limit, $key);
        }
    }
}
