<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RolesAndPermissionsSeeder::class);
        $this->call(CreateAdminSeeder::class);
        $this->call(CreateDoctorSeeder::class);
        $this->call(CreatePharmacistSeeder::class);
        $this->call(CreatePatientSeeder::class);

        $this->call(BranchSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(PrescriptionSeeder::class);
        // \App\Models\User::factory(10)->create();
    }
}
