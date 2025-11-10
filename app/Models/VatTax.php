<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class VatTax extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * Get the products that own the VatTax
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_vat_taxes');
    }
}
