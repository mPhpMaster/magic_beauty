<?php

namespace Database\Seeders;

use App\Models\PayType;
use Illuminate\Database\Seeder;

class PayTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PayType::create([
            'name_en' => 'On Delivery',
            'name_ar' => 'استلام عند التوصيل',
        ]);
    }
}
