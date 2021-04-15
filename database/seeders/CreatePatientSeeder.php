<?php

namespace Database\Seeders;

use App\Interfaces\IRoleConst;
use App\Models\User;
use Illuminate\Database\Seeder;

class CreatePatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory(random_int(1, 50))->create()
            ->map->assignRole(IRoleConst::PATIENT_ROLE)
            ->map(fn($user) => $user->assignCreator(User::onlyDoctors()->ByActive()->inRandomOrder()->first()));
    }
}
