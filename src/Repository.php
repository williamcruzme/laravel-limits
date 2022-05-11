<?php

namespace Williamcruzme\Limits;

use Williamcruzme\Limits\Limit;

class Repository
{
    public function __construct(
        protected $name,
        protected array $items
    ) {}

    public function get($key)
    {
        return $this->items[$key] ??= Limit::resolveDefaultLimit($this->name, $key);
    }

    public function set($key, $value)
    {
        $this->items[$key] = $value;

        Limit::user()->limits()->updateOrCreate([], [
            "{$this->name}->{$key}" => $value,
        ]);
    }

    public function has($key)
    {
        return ! is_null($this->get($key));
    }

    public function remaining()
    {
        $class = new Limit::$limits[$this->name];
        $remamingLimits = $class->handle(Limit::user(), $this);

        return new self($this->name, $remamingLimits + $this->items);
    }
}
