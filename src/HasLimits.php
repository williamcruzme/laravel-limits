<?php

namespace Williamcruzme\Limits;

use Williamcruzme\Limits\Models\UserLimit;

trait HasLimits
{
    public function limits()
    {
        return $this->hasOne(UserLimit::class);
    }
}
