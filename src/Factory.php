<?php

namespace Williamcruzme\Limits;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class Factory
{
    public static $limits = [];

    public static $defaultLimitCallback;

    protected $user;

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

    public function actingAs(Authenticatable $user)
    {
        $this->user = $user;

        return $this;
    }

    public function user()
    {
        return $this->user;
    }

    public function __call($name, $arguments)
    {
        $limits = Cache::rememberForever("users:{$this->user->id}:limits", function () {
            $this->user->load('limits');
            return $this->user->limits;
        });

        return new Repository(Str::snake($name), $limits[$name] ?? []);
    }
}
