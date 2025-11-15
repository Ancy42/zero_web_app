<?php

namespace Modules\Purchase\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseReturn extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

     public function purchaseReturnProducts()
    {
        return $this->hasMany(PurchaseReturnProduct::class);
    }

}
