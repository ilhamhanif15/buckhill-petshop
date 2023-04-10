<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    const UUID_COLUMN = 'uuid';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uuid',
        'title',
        'slug',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!isset($model->{self::UUID_COLUMN})) {
                $model->{self::UUID_COLUMN} = Str::orderedUuid()->toString();
            }
        });
    }
}
