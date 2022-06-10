<?php

namespace Williamcruzme\Limits\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Williamcruzme\Limits\Facades\Limit;

class UserLimit extends Model
{
    protected $guarded = [];

    protected static function booted()
    {
        $callback = fn ($model) => Cache::forget("users:$model->user_id:limits");

        self::saved($callback);
        self::deleted($callback);
    }

    public function getCasts()
    {
        return once(fn () =>
            collect(Limit::limits())
                ->mapWithKeys(fn ($class, $key) => [$key => 'json'])
                ->toArray() + parent::getCasts()
        );
    }
}
