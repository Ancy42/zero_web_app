<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Blog extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $appends = ['thumbnail'];

    /**
     * Get the user associated with the blog.
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * Get the category associated with the blog.
     */
    public function category(): HasOne
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }

    /**
     * Get the views associated with the blog.
     */
    public function views(): HasMany
    {
        return $this->hasMany(BlogView::class);
    }

    /**
     * Get the tags associated with the blog.
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'blog_tags');
    }

    /**
     * Generate a thumbnail for the media, if available, or use the default image.
     *
     * @return Attribute The generated thumbnail attribute.
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

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($blog) {
            $blog->slug = $blog->generateUniqueSlug($blog->title);
        });

        static::updating(function ($blog) {
            $blog->slug = $blog->generateUniqueSlug($blog->title, $blog->id);
        });
    }

    /**
     * Generate a unique slug.
     *
     * @param  string  $title
     * @return string
     */
    public function generateUniqueSlug($title, $acceptId = null)
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;

        while (self::where('slug', $slug)->where('id', '!=', $acceptId)->exists()) {
            $slug = $counter.'-'.$originalSlug;
            $counter++;
        }

        return $slug;
    }
}
