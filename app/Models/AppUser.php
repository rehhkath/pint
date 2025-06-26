<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;

class AppUser extends Authenticatable
{
    use HasFactory;
    protected $table = 'app_users';

    protected $fillable = [
        'id',
        'username',
        'password',
        'api_token',
        'stores',
        'role',
        'cigam_id',
    ];

    protected $hidden = [
        'password',
        'api_token',
    ];

    protected $casts = [
        'stores'   => 'array',
        'cigam_id' => 'integer',
    ];

    public function setPasswordAttribute($value)
    {
        if (!empty($value)) {
            
            $this->attributes['password'] = Hash::needsRehash($value) 
                ? Hash::make($value) 
                : $value;
        }
    }

    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }
}
