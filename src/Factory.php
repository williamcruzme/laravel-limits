<?php

namespace Williamcruzme\Limits;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class Factory
{
    protected $user;

    public function __construct()
    {
        $this->user = Auth::guard('sanctum')->user();
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
