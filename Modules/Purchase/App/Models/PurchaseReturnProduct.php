<?php

namespace Modules\Purchase\App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseReturnProduct extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function purchaseReturn()
    {
        return $this->belongsTo(PurchaseReturn::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function returnProductSkus()
    {
        return $this->hasMany(ReturnProductSku::class);
    }
}
