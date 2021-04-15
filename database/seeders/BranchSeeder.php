<?php

namespace Database\Seeders;

use App\Interfaces\IRoleConst;
use App\Models\Branch;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Branch::factory(random_int(1, 2))->create();
    }
}
