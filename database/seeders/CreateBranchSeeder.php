<?php

namespace Database\Seeders;

use App\Interfaces\IRoleConst;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Database\Seeder;

class CreateBranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Branch::create([
            "user_id" => ($p = User::onlyPharmacists()->first()) ? $p->id : 0,
            "name_en" => "Main",
            "name_ar" => "الرئيسي",
            "location" => "",
            "status" => "active",
        ]);
    }
}
