<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Requests extends Model
{
    use HasFactory;
    protected $table = 'requests';

    protected $fillable = [
        'id',
        'title',
        'description',
        'type',
        'status',
        'created_at',
        'updated_at',
        'user_id',
        'company_id'
    ];
}
