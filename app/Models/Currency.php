<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Currency extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected static function boot()
    {
        parent::boot();

        static::created(function () {
            Cache::forget('currencies');
        });

        static::updated(function () {
            Cache::forget('currencies');
        });

        static::deleted(function () {
            Cache::forget('currencies');
        });
    }
}
