<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use App\Models\Scopes\hasSubscription;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[ScopedBy([hasSubscription::class])]
class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id'];

    protected $appends = ['thumbnail'];

    /**
     * Retrieve the shop that this model belongs to.
     *
     * @return BelongsTo The shop that this model belongs to.
     */
    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    /**
     * get the translations that owns the product.
     */
    public function translations(): HasMany
    {
        return $this->hasMany(ProductTranslation::class);
    }

    /**
     * Retrieve the categories associated with the current model.
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'product_categories');
    }


    /**
     * Retrieve the flash sales associated with the model.
     *
     * @return BelongsToMany The flash sales associated with the model.
     */
    public function flashSales(): BelongsToMany
    {
        $currentDateTime = Carbon::now();

        return $this->belongsToMany(FlashSale::class, 'flash_sale_products', 'product_id', 'flash_sale_id')
            ->withPivot('price', 'quantity', 'discount', 'sale_quantity')
            ->where('status', 1)
            ->where(function ($query) use ($currentDateTime) {
                $query->where('start_date', '<=', $currentDateTime->toDateString())
                    ->where('end_date', '>=', $currentDateTime->toDateString())
                    ->where(function ($query) use ($currentDateTime) {
                        $query->where('start_time', '<=', $currentDateTime->toTimeString())
                            ->orWhere('end_time', '>=', $currentDateTime->toTimeString());
                    });
            })->latest('id');
    }

    /**
     * Get the video media record associated with the model.
     *
     * @return BelongsTo The video media record associated with the model.
     */
    public function videoMedia(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'video_id');
    }

    /**
     * Retrieves the video associated with the model.
     *
     * @return Attribute The video attribute.
     */
    public function video(): Attribute
    {
        $video = null;

        if ($this->videoMedia && $this->videoMedia->type == 'file' && Storage::exists($this->videoMedia->src)) {
            $video = (object) [
                'id' => $this->videoMedia->id,
                'url' => Storage::url($this->videoMedia->src),
                'type' => $this->videoMedia->type,
            ];
        } elseif ($this->videoMedia && $this->videoMedia->type != 'file' && $this->videoMedia->src != null) {
            $video = (object) [
                'id' => $this->videoMedia->id,
                'url' => $this->videoMedia->src,
                'type' => $this->videoMedia->type,
            ];
        }

        return new Attribute(
            get: fn() => $video
        );
    }

    /**
     * Create a thumbnail for the media, with a default image if none is present.
     */
    public function thumbnail(): Attribute
    {
        $thumbnail = asset('default/default.jpg');
        if ($this->product_thumbnail && Storage::exists($this->product_thumbnail)) {
            $thumbnail = Storage::url($this->product_thumbnail);
        }

        return new Attribute(
            get: fn() => $thumbnail
        );
    }

    /**
     * Get the medias for the product.
     */
    public function medias(): HasMany
    {
        return $this->hasMany(ProductThumbnail::class, 'product_id');
    }

    //  public function medias(): BelongsToMany
    // {
    //     return $this->belongsToMany(ProductThumbnail::class, 'product_thumbnails');
    // }

    /**
     * Generate thumbnails for the medias.
     */
    public function thumbnails(): Collection
    {
        $thumbnails = collect([]);

        if (request()->is('api/*')) {
            if ($this->videoMedia && $this->videoMedia->type == 'file' && Storage::exists($this->videoMedia->src)) {
                $thumbnails[] = (object) [
                    'id' => $this->videoMedia->id,
                    'thumbnail' => null,
                    'url' => Storage::url($this->videoMedia->src),
                    'type' => $this->videoMedia->type,
                ];
            } elseif ($this->videoMedia && $this->videoMedia->type != 'file' && $this->videoMedia->src != null) {
                $thumbnails[] = (object) [
                    'id' => $this->videoMedia->id,
                    'thumbnail' => null,
                    'url' => $this->videoMedia->src,
                    'type' => $this->videoMedia->type,
                ];
            }

            $thumbnails[] = (object) [
                'id' => $this->media?->id,
                'thumbnail' => $this->thumbnail,
                'url' => null,
                'type' => 'image',
            ];
        }

        foreach ($this->medias as $media) {
            $thumbnail = asset('default/default.jpg');
            if ($media && Storage::exists($media->additional_thumbnail)) {
                $thumbnail = Storage::url($media->additional_thumbnail);
            }
            $thumbnails[] = (object) [
                'id' => $media?->id,
                'thumbnail' => $thumbnail,
                'url' => null,
                'type' => 'image',
            ];
        }

        return $thumbnails;
    }

    /**
     * Generate additional thumbnails for the medias.
     */
    public function additionalThumbnails(): Collection
    {
        $thumbnails = collect([]);
        foreach ($this->medias as $media) {
            $thumbnail = asset('default/default.jpg');
            if ($media && Storage::exists($media->additional_thumbnail)) {
                $thumbnail = Storage::url($media->additional_thumbnail);
            }
            $thumbnails[] = (object) [
                'id' => $media?->id,
                'thumbnail' => $thumbnail,
            ];
        }

        return $thumbnails;
    }

    /**
     * Retrieves the reviews associated with this object.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany The reviews associated with this object.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
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
            get: fn() => (float) number_format($avgRating > 0 ? $avgRating : 0, 1, '.', '')
        );
    }


    /**
     * get the brand that owns the product.
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }


    /**
     * Retrieve the orders associated with the model.
     */
    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class, 'order_products')->withPivot('quantity', 'price');
    }

    /**
     * Filter the given builder by active status.
     *
     * @param  Builder  $builder  The builder to filter.
     * @return Builder The filtered builder.
     */
    public function scopeIsActive(Builder $builder)
    {
        return $builder->where('is_active', true)->where('is_approve', true)->whereHas('shop', function ($query) {
            return $query->whereHas('user', function ($query) {
                $query->where('is_active', 1);
            });
        });
    }

    /**
     * Calculate the discount percentage based on the given price and discount price.
     */
    public static function getDiscountPercentage($price, $discountPrice)
    {
        return $discountPrice ? ($price - $discountPrice) * 100 / $price : 0;
    }

    /**
     * get the favorites from the model.
     */
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            $product->slug = $product->generateUniqueSlug($product->slug ?? $product->name);
        });

        static::updating(function ($product) {
            $product->slug = $product->generateUniqueSlug($product->slug ?? $product->name, $product->id);
        });
    }

    /**
     * Generate a unique slug.
     *
     * @param  string  $title
     * @return string
     */
    public function generateUniqueSlug($name, $acceptId = null)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        while (self::where('slug', $slug)->where('id', '!=', $acceptId)->exists()) {
            $slug = $counter . '-' . $originalSlug;
            $counter++;
        }

        return $slug;
    }

    public function categoryAttributes(): BelongsToMany
    {
        return $this->belongsToMany(CategoryAttribute::class, 'product_category_attributes');
    }

    public function purchaseProducts(): ?HasMany
    {
        if (function_exists('module_exists') && module_exists('Purchase')) {
            return $this->hasMany(\Modules\Purchase\App\Models\PurchaseProduct::class);
        }
        return null;
    }
    public function productStockOuts(): ?HasMany
    {
        if (function_exists('module_exists') && module_exists('Purchase')) {
            return $this->hasMany(\Modules\Purchase\App\Models\ProductStockOut::class);
        }
        return null;
    }
    public function orderProducts(): HasMany
    {
        return $this->hasMany(OrderProduct::class);
    }
}
