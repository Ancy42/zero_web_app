<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubscriptionPlan extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function subscriptions(): HasMany
    {
        return $this->hasMany(ShopSubscription::class, 'plan_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }
}
