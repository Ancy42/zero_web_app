<?php

namespace Modules\Purchase\App\Models;

use App\Models\User;
use App\Models\Media;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Supplier extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = ['id'];

    public function media()
    {
        return $this->belongsTo(Media::class, 'media_id');
    }


    public function thumbnail(): Attribute
    {
        $thumbnail = asset('default/profile.jpg');
        if ($this->media && Storage::exists($this->media->src)) {
            $thumbnail = Storage::url($this->media->src);
        }

        return Attribute::make(
            get: fn() => $thumbnail
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(SupplierTransaction::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
}
