<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Companys extends Model
{
    use HasFactory;
    protected $table = 'companys';

    protected $fillable = [
        'id',
        'name',
        'logo',
        'number_of_employees',
        'industry',
        'address',
        'latitude',
        'longitude',
        'description',
        'email_company',
        'n_siret',
        'status',
        'created_at',
        'updated_at',
    ];
}
