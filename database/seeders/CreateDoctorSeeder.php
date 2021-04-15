<?php

namespace Database\Seeders;

use App\Interfaces\IRoleConst;
use App\Models\User;
use Illuminate\Database\Seeder;

class CreateDoctorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::factory(random_int(1, 50))->create()
            ->map->assignRole(IRoleConst::DOCTOR_ROLE);
    }
}
