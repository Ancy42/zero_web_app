<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * The blogs that belong to the Tag
     */
    public function blogs(): BelongsToMany
    {
        return $this->belongsToMany(Blog::class);
    }
}
