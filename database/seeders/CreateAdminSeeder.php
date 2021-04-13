<?php

namespace Database\Seeders;

use App\Interfaces\IRoleConst;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CreateAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // Create Support Users
        $support_user = config('app.support_user');
        $user = \App\Models\User::firstOrCreate(array_only($support_user, 'id'), array_except($support_user, ['id']));
        $update = [
            'id' => data_get($support_user, 'id'),
//            'role_id' => data_get($support_user, 'role_id'),
        ];
        if ( isset($support_user['password']) && Hash::needsRehash(trim($support_user['password'])) ) {
            $update['password'] = Hash::make($support_user['password']);
        }
        $user->update($update);
        $user->assignRole(config('app.support_role.name', IRoleConst::SUPPORT_ROLE));
    }
}
