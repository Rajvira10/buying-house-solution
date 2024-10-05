<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Role;
use App\Models\Buyer;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
    }

    public function hasPermission($permission_name)
    {
        foreach($this->roles as $role)
        {
            if($role->name == "super_admin")
            {
                return true;
            }

            foreach($role->permissions as $permission)
            {
                if($permission->name == $permission_name)
                {
                    return true;
                }
            }
        }

        return false;
    }

    public function assignRole($role)
    {
        return $this->roles()->attach($role);
    }

    public function getPermissions()
    {
        $permissions = [];

        foreach($this->roles as $role)
        {
            if($role->name == "super_admin")
            {
                $permissions = "all";

                break;
            }

            foreach($role->permissions as $permission)
            {
                $permissions[] = $permission->name;
            }
        }

        return $permissions;
    }

    public function getRoles()
    {
        $roles = [];

        foreach($this->roles as $role)
        {
            $roles[] = $role->name;
        }

        return $roles;
    }

    public function buyer()
    {
        return $this->hasOne(Buyer::class);
    }

}
