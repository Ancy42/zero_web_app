<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsAppContact extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function messages()
    {
        return $this->hasMany(WhatsAppMessage::class , 'contact_id');
    }
    
}
