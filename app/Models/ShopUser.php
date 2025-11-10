<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopUser extends Model
{
    use HasFactory;

    protected $table = 'shop_user';

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function shopUserChats()
    {
        return $this->hasMany(ShopUserChats::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function latestMessage()
    {
        return $this->hasOne(ShopUserChats::class)
            ->latestOfMany(); // gets the latest chat for this shop_user
    }
}
