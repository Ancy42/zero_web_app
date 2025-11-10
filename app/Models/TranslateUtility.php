<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TranslateUtility extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }


    public function brands(): HasMany
    {
        return $this->hasMany(Brand::class);
    }


}
