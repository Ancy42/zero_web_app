<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FooterItem extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function footer()
    {
        return $this->belongsTo(Footer::class);
    }
}
