<?php 

namespace App\Traits;

use Illuminate\Support\Str;

trait Uuids
{
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!isset($model->{$model->getUuidColName()})) {
                $model->{$model->getUuidColName()} = Str::orderedUuid()->toString();
            }
        });
    }
}