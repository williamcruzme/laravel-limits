<?php

namespace Williamcruzme\Limits;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class Limit
{
    public static $user;

    public static $limits = [];

    public static $defaultLimitCallback;

    public static function actingAs(Authenticatable $user)
    {
        static::$user = $user;

        return new static;
    }

    public static function user()
    {
        return static::$user;
    }

    public static function register($key, $class)
    {
        static::$limits[$key] = $class;
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

    public static function __callStatic($name, $arguments)
    {
        $limits = Cache::rememberForever('users:'.static::$user->id.':limits', function () {
            static::$user->load('limits');
            return static::$user->limits;
        });

        return new Repository(Str::snake($name), $limits[$name] ?? []);
    }
}
