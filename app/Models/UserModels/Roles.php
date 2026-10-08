<?php

namespace App\Models\UserModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'is_active'
        ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
    
    public function permissions()
    {
        return $this->belongsToMany(
            Permission::class,
            'role_permission',
            'role_id',
            'permission_id'
        )->withTimestamps();
    }

}
