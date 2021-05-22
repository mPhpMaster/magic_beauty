<?php

namespace Database\Seeders;

use App\Imports\DoctorsImport;
use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;

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

        Excel::import(new DoctorsImport(), base_path("doctors_template.xlsx"));
//        $this->call(CreateDoctorSeeder::class);
        $this->call(CreatePharmacistSeeder::class);
        $this->call(CreatePatientSeeder::class);

        $this->call(BranchSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(PrescriptionSeeder::class);
        // \App\Models\User::factory(10)->create();
    }
}
