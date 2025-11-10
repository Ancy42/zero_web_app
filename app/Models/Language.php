<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Language extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected static function boot()
    {
        parent::boot();

        static::created(function () {
            Cache::forget('languages');
        });

        static::updated(function () {
            Cache::forget('languages');
        });

        static::deleted(function () {
            Cache::forget('languages');
        });
    }
    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class);
    }

    public function flag(): Attribute
    {
        $thumbnail = asset('assets/language') . '/' . $this->name . '.png';
       if ($this->media && Storage::exists($this->media->src)) {
            $thumbnail = Storage::url($this->media->src);
       }

        return Attribute::make(
            get: fn() => $thumbnail
        );
    }
}
