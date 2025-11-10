<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoogleReCaptcha extends Model
{
    use HasFactory;

    protected $fillable = [
        'site_key',
        'secret_key',
        'is_active',
    ];
}
