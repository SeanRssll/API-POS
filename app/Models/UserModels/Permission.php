<?php

namespace App\Models\UserModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\UserModels\Roles;
use App\Models\User;

class Permission extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'is_active'
    ];
    public function roles()
    {
        return $this->belongsToMany(
            Roles::class,
            'role_permission',
            'permission_id',
            'role_id'
        )->withTimestamps();
    }
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

}
