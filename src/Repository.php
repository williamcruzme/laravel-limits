<?php

namespace Williamcruzme\Limits;

use Williamcruzme\Limits\Limit;

class Repository
{
    public function __construct(
        public $name,
        protected array $items
    ) {}

    public function get($key)
    {
        return $this->items[$key] ??= Limit::resolveDefaultLimit($this->name, $key);
    }

    public function set(...$values)
    {
        if (is_array($values[0])) {
            $attributes = $values[0];
        } else {
            $attributes = [
                $values[0] => $values[1],
            ];
        }

        $this->items = $attributes + $this->items;

        Limit::user()->limits()->updateOrCreate([], [$this->name => $this->items]);
    }

    public function all()
    {
        foreach (Limit::resolve($this)->keys as $key) {
            $this->get($key);
        }

        return $this->items;
    }

    public function has($key)
    {
        return ! is_null($this->get($key));
    }

    public function remaining()
    {
        $remainingLimits = Limit::resolve($this)->handle(Limit::user(), $this);

        return new self($this->name, $remainingLimits + $this->items);
    }
}
