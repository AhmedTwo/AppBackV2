<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;
    protected $table = 'favoris';

    protected $fillable = [
        'id',
        'user_id',
        'offer_id',
        'created_at',
        'updated_at',
    ];

    public function users()
    {
        return $this->belongsTo(\App\Models\Favorite::class, 'user_id'); // sert a communiquer via les clefs etrangere 
    }

    public function job_offers()
    {
        return $this->hasMany(\App\Models\Favorite::class, 'offer_id');
    }
}
