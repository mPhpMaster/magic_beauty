<?php

namespace Database\Seeders;

use App\Interfaces\IRoleConst;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CreatePharmacistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::create([
            'email' => 'ccefaegpt@gmail.com',
            'name_en' => 'Hossam Mahmoud',
            'name_ar' => 'Hossam Mahmoud',
            'password' => Hash::make(parseMobile("534717071")),
            'mobile' => parseMobile("534717071"),
            'location' => "",
//        'role_id' => 1,
        ])->assignRole(IRoleConst::PHARMACIST_ROLE);
//        User::factory(random_int(1, 50))->create()
//            ->map->assignRole(IRoleConst::PHARMACIST_ROLE);
    }
}
