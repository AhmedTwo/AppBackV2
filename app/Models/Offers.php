<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offers extends Model
{
    use HasFactory;
    protected $table = 'offers';

    protected $fillable = [
        'id',
        'title',
        'description',
        'mission',
        'location',
        'category',
        'employment_type_id',
        'technologies_used',
        'benefits',
        'participants_count',
        'image_url',
        'created_at',
        'updated_at',
        'id_company',
    ];
}
