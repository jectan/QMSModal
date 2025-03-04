<?php

namespace App\Traits;

use App\Models\Role;

trait HasRole
{
    public function hasRole($roles)
    {
        foreach ($roles as $role) {
            if ($this->role->id == $role) {
                return true;
            }
        }
        return false;
    }

    public function role(){
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }
}
