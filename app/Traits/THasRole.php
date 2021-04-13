<?php

namespace App\Traits;

trait THasRole
{

    public function role(): ?\App\Models\Role
    {
        return ($r = $this->roles()) ? $r->first() : null;
    }

    public function getRoleNameAttribute(): string
    {
        return ($role = $this->role()) ? $role->name : "";
    }
}
