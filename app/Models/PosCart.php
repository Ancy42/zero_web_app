<?php

namespace App\Models;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PosCart extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function products(): BelongsToMany
    {
        $pivotColumns = ['id', 'quantity', 'brand'];

        if (Schema::hasColumn('pos_cart_products', 'sku_no')) {
            $pivotColumns[] = 'sku_no';
        }
        return $this->belongsToMany(Product::class, 'pos_cart_products')
                    ->withPivot($pivotColumns)
                    ->withTimestamps();
        }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }
}
