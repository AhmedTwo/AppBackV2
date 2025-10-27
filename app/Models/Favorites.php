<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorites extends Model
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
}
