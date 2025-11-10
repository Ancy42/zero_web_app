<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OfferBanner extends Model
{
    use HasFactory;

    protected $guarded = ['id'];


    public function banner(): Attribute
    {
        $logo = asset('assets/offerBanner') . '/' . $this->homeTheme->theme_name  . '/' . $this->alias . '.png';

        if ($this->image && Storage::exists($this->image)) {
            $logo = Storage::url($this->image);
        }elseif($this->image && asset($this->image)){
            $logo = asset($this->image);
        }
        return Attribute::make(
            get: fn() => $logo
        );
    }

    public function homeTheme(): BelongsTo
    {
        return $this->belongsTo(HomeTheme::class);
    }
}
