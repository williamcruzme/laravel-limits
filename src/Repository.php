<?php

namespace Williamcruzme\Limits;

use Williamcruzme\Limits\Limit;

class Repository
{
    protected $name;

    protected $items;

    public function __construct($name, array $items)
    {
        $this->name = $name;
        $this->items = array_filter($items);
    }

    public function get($key)
    {
        return $this->items[$key] ?? Limit::resolveDefaultLimit($this->name, $key);
    }

    public function set($key, $value)
    {
        $this->items[$key] = $value;

        Limit::user()->limits()->updateOrCreate([], [
            "{$this->name}->{$key}" => $value,
        ]);
    }

    public function remaining()
    {
        $class = new Limit::$limits[$this->name];
        $remamingLimits = $class->handle(Limit::user(), $this);

        return new self($this->name, $remamingLimits + $this->items);
    }
}
