<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\HasApiTokens;
use App\Models\UserModels\Roles;
use App\Models\UserModels\Permission;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Attributes that can be mass assigned.
     */
    protected $fillable = [
        'employee_id',
        'f_name',
        'm_name',
        'l_name',
        'gender',
        'house_number',
        'street',
        'barangay',
        'city',
        'province',
        'postal_code',
        'birthday',
        'contact_num',
        'profile_photo',
        'role_id',
        'date_hire',
        'email',
        'password',
        'is_active',
    ];

    /**
     * Attributes hidden from array and JSON output.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Attribute casting.
     */
    protected $casts = [
        'birthday' => 'date',
        'date_hire' => 'date',
        'is_active' => 'boolean',
        'email_verified_at' => 'datetime',
    ];

    /**
     * Automatically generate employee ID when creating a user.
     *
     * Format: YYYYMMDDMMDDSS
     * YYYYMMDD = Hiring date (current date)
     * MMDD     = Birthday month and day
     * SS       = Sequence number
     */
    protected static function booted()
    {
        static::creating(function ($user) {
            if (empty($user->employee_id) && $user->birthday) {
                $hiringDate = $user->date_hire->format('Ymd');
                $birthday = $user->birthday->format('md');

                $prefix = $hiringDate . $birthday;

                $lastEmployeeId = DB::table('users')
                    ->where('employee_id', 'like', $prefix . '%')
                    ->orderBy('employee_id', 'desc')
                    ->value('employee_id');

                $sequence = $lastEmployeeId
                    ? ((int) substr($lastEmployeeId, -2)) + 1
                    : 1;

                if ($sequence > 99) {
                    throw new \RuntimeException(
                        'Employee ID sequence limit reached for this hiring date and birthday.'
                    );
                }

                $user->employee_id = $prefix . str_pad(
                    $sequence,
                    2,
                    '0',
                    STR_PAD_LEFT
                );
            }
        });
    }

    /**
     * User belongs to one role.
     */
    public function role()
    {
        return $this->belongsTo(Roles::class, 'role_id');
    }

    /**
     * Permissions assigned directly to this user.
     */
    public function permissions()
    {
        return $this->belongsToMany(
            Permission::class,
            'user_permissions',
            'user_id',
            'permission_id'
        )->withTimestamps();
    }

    /**
     * Get all permissions inherited from the role
     * and assigned directly to the user.
     */
    public function allPermissions()
    {
        $rolePermissions = $this->role
            ? $this->role->permissions
            : collect();

        return $rolePermissions
            ->merge($this->permissions)
            ->unique('id')
            ->values();
    }

    /**
     * Check whether the user has a specific permission.
     */
    public function hasPermission($permission)
    {
        return $this->allPermissions()
            ->contains('name', $permission);
    }

    /**
     * Get the user's full name.
     */
    public function getFullNameAttribute()
    {
        return trim(implode(' ', array_filter([
            $this->f_name,
            $this->m_name,
            $this->l_name,
        ])));
    }

    /**
     * Get the user's formatted address.
     */
    public function getFullAddressAttribute()
    {
        return implode(', ', array_filter([
            $this->house_number,
            $this->street,
            $this->barangay,
            $this->city,
            $this->province,
            $this->postal_code,
        ]));
    }
}