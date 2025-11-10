<?php

namespace App\Models;

use Illuminate\Support\Str;
use App\Enums\SubscriptionStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Shop extends Model
{
    use HasFactory ,SoftDeletes;

    protected $guarded = ['id'];

    /**
     * Get the shop user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(ShopSubscription::class);
    }

    /**
     * get emploees for this shop
     */
    public function employees(): HasMany
    {
        return $this->hasMany(User::class, 'shop_id');
    }

    /**
     * get withdraw model for this user.
     */
    public function withdraws(): HasMany
    {
        return $this->hasMany(Withdraw::class, 'shop_id');
    }

    /**
     * get all gallery images for this shop
     */
    public function galleries(): HasMany
    {
        return $this->hasMany(Gallery::class, 'shop_id');
    }

    /**
     * Get the logo for the Shop as an attribute.
     */
    public function logo(): Attribute
    {
        $logo = asset('default/default.jpg');
        if ($this->shop_logo && Storage::exists($this->shop_logo)) {
            $logo = Storage::url($this->shop_logo);
        }

        return Attribute::make(
            get: fn() => $logo
        );
    }

    /**
     * Get the banner for the Shop as an attribute.
     */
    public function banner(): Attribute
    {
        $banner = 'https://placehold.co/2000x500/png';
        if ($this->shop_banner && Storage::exists($this->shop_banner)) {
            $banner = Storage::url($this->shop_banner);
        }

        return Attribute::make(
            get: fn() => $banner
        );
    }

    /**
     * Get all of the products for the Shop.
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }



    /**
     * get all of the brands for the shop.
     */
    public function brands(): HasMany
    {
        return $this->hasMany(Brand::class);
    }

    /**
     * Get all of the coupons for the Shop.
     */
    public function coupons(): HasMany
    {
        return $this->hasMany(Coupon::class);
    }

    /**
     * Get all of the orders for the Shop.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get all of the banners for the Shop.
     */
    public function banners(): HasMany
    {
        return $this->hasMany(Banner::class, 'shop_id');
    }

    /**
     * Scope a query to only include active shops.
     *
     * @param  Builder  $builder  The query builder
     * @return mixed
     */
    public function scopeIsActive(Builder $builder)
    {
        return $builder->whereHas('user', function ($query) {
            $query->where('is_active', 1);
        });
    }

    /**
     * Get all of the reviews for the Shop.
     *
     * @return HasMany.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'shop_id');
    }

    public function currentSubscription(): Attribute
    {
        $subscription = $this->subscriptions()->where('status', SubscriptionStatus::ACTIVE)
            ->where(function ($q) {
                $q->whereNull('ends_at')
                    ->orWhere('ends_at', '>', now());
            })
            ->where(function ($q) {
                $q->whereNull('remaining_sales')
                    ->orWhere('remaining_sales', '>', 0);
            })
            ->first();

        return new Attribute(
            get: fn() => $subscription,
        );
    }

    /**
     * Calculates the average rating of the reviews.
     *
     * @return Attribute The average rating attribute.
     */
    public function averageRating(): Attribute
    {
        $avgRating = $this->reviews()->avg('rating');

        return new Attribute(
            get: fn() => (float) number_format($avgRating > 0 ? $avgRating : 5, 1, '.', ''),
        );
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($shop) {
            $shop->slug = $shop->generateUniqueSlug($shop->slug ?? $shop->name, $shop->id);
        });
    }

    public function generateUniqueSlug($name, $acceptId = null)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        while (self::where('slug', $slug)->where('id', '!=', $acceptId)->exists()) {
            $slug = "{$originalSlug}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}
