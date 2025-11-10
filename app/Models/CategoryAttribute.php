<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CategoryAttribute extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function scopeValid($query)
    {
        return $query->where('category_id', '>', 0)->whereNull('parent_id');
    }
    public function subAttributes(): HasMany
    {
        return $this->hasMany(CategoryAttribute::class, 'parent_id');
    }
    public function attributeGet(): BelongsTo
    {
        return $this->belongsTo(CategoryAttribute::class, 'parent_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_category_attributes');
    }
}
