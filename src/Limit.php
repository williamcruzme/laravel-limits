<?php

namespace Williamcruzme\Limits;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Cache;

class Limit
{
    public static $user;

    public static $limits = [];

    public static $defaultLimitCallback;

    public static function actingAs(Authenticatable $user)
    {
        static::$user = $user;
    }

    public static function user()
    {
        return static::$user;
    }

    public static function register($key, $class)
    {
        static::$limits[$key] = $class;
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

    public static function __callStatic($name, $arguments)
    {
        $limits = Cache::rememberForever('users:'.static::$user->id.':limits', function () {
            static::$user->load('limits');
            return static::$user->limits;
        });

        return new Repository($name, $limits[$name] ?? []);
    }
}
