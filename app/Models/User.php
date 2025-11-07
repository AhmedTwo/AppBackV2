<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class User extends Model
{
    use HasFactory;
    use HasApiTokens;
    protected $table = 'users';

    protected $fillable = [
        'id',
        'nom',
        'prenom',
        'email',
        'password',
        'role',
        'telephone',
        'ville',
        'code_postal',
        'cv_pdf',
        'qualification',
        'preference',
        'disponibilite',
        'photo',
        'created_at',
        'updated_at',
        'company_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function requests()
    {
        return $this->hasMany(\App\Models\Request::class, 'users_id');
    }

    public function companys()
    {
        return $this->hasMany(\App\Models\Company::class, 'id');
    }

    public function getRole(): string
    {
        return $this->role;
    }
}
