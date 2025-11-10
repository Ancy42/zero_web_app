<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Banner extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }


    public function thumbnail(): Attribute
    {
        $thumbnail = asset('default/default.jpg');
        if ($this->banner && Storage::exists($this->banner)) {
            $thumbnail = Storage::url($this->banner);
        }

        return Attribute::make(
            get: fn () => $thumbnail
        );
    }

}
