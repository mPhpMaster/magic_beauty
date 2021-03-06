<?php

namespace Database\Seeders;

use App\Interfaces\IRoleConst;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[ PermissionRegistrar::class ]->forgetCachedPermissions();

        // create permissions
//        Permission::create(['name' => 'add']);
//        Permission::create(['name' => 'edit']);
//        Permission::create(['name' => 'show']);
//        Permission::create(['name' => 'delete']);
        $permissions_cats = collect(config('app.permissions'))->map(
            fn($cats, $_permission) => array_map(fn($cat) => ['name' => "{$_permission}.{$cat}"], $cats)
        );
        $permissions_cats->flatten(1)->map(fn($p) => Permission::firstOrCreate($p));

        // create roles and assign existing permissions
        $role_support = Role::firstOrCreate(['name' => IRoleConst::SUPPORT_ROLE]);
        if ( trim($role_support->id) !== trim(config('app.support_role.id')) ) {
            $role_support->update(['id' => config('app.support_role.id')]);
            $role_support = $role_support->refresh();
        }
        $role_administrator = Role::firstOrCreate(['name' => IRoleConst::ADMINISTRATOR_ROLE]);
        $role_pharmacist = Role::firstOrCreate(['name' => IRoleConst::PHARMACIST_ROLE]);
        $role_doctor = Role::firstOrCreate(['name' => IRoleConst::DOCTOR_ROLE]);
        $role_patient = Role::firstOrCreate(['name' => IRoleConst::PATIENT_ROLE]);

        $all_permissions = Permission::all();
        $role_support->givePermissionTo($all_permissions);
        $role_administrator->givePermissionTo($all_permissions);

        $role_pharmacist->givePermissionTo($permissions_cats->only([
            'prescriptions',
            'orders',
            'pay_types.index',
            'pay_types.show',
            'categories.index',
            'categories.show',
            'branches.index',
            'branches.show',
            'branches.edit',
            'products',
            'reports',
        ])->flatten(1)->toArray());

        $role_doctor->givePermissionTo($permissions_cats->only([
            'prescriptions',
            'orders',
            'pay_types.index',
            'pay_types.show',
            'categories.index',
            'categories.show',
            'branches.index',
            'branches.show',
            'products',
            'reports',
        ])->flatten(1)->toArray());

        $role_patient->givePermissionTo($permissions_cats->only([
            'prescriptions.index',
            'prescriptions.show',
            'categories.index',
            'categories.show',
            'products.index',
            'products.show',
            'pay_types.index',
            'pay_types.show',
            'orders',
            'categories.index',
            'categories.show',
            'branches.index',
            'branches.show',
            'reports',
        ])->flatten(1)->toArray());

        // gets all permissions via Gate::before rule; see AuthServiceProvider

    }
}
