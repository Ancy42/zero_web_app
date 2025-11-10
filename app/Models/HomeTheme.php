<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeTheme extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function offerBanners()
    {
        return $this->hasMany(OfferBanner::class);
    }
}
