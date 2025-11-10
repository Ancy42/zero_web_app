<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class FlashSale extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * Get the products for the flash sale.
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'flash_sale_products')->withPivot('price', 'quantity', 'discount', 'sale_quantity');
    }


    /**
     * Create a thumbnail for the media, with a default image if none is present.
     */
    public function thumbnail(): Attribute
    {
        $thumbnail = asset('default/default.jpg');
        if ($this->image && Storage::exists($this->image)) {
            $thumbnail = Storage::url($this->image);
        }

        return new Attribute(
            get: fn () => $thumbnail
        );
    }

    public function scopeIsActive($query)
    {
        $currentDateTime = Carbon::now();

        return $query->where('status', 1)->where(function ($query) use ($currentDateTime) {
            $query->where('start_date', '<=', $currentDateTime->toDateString())
                ->where('end_date', '>=', $currentDateTime->toDateString())
                ->where(function ($query) use ($currentDateTime) {
                    $query->where('start_time', '<=', $currentDateTime->toTimeString())
                        ->orWhere('end_time', '>=', $currentDateTime->toTimeString());
                });
        })->latest('id');
    }
}
