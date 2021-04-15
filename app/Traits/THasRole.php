<?php

namespace App\Traits;

trait THasRole
{

//    public function role(): ?\App\Models\Role
//    {
//        return ($r = $this->roles()) ? $r->first() : null;
//    }

    public function getRoleNameAttribute(): string
    {
        $r = ($r = $this->roles()) ? $r->first() : null;
        return ($role = $r) ? $role->name : "";
    }
}
