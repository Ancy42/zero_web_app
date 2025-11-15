<?php

namespace Modules\Purchase\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReturnProductSku extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function purchaseReturnProduct()
    {
        return $this->belongsTo(PurchaseReturnProduct::class);
    }
}
