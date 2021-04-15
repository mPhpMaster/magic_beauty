<?php

namespace Database\Seeders;

use App\Models\Prescription;
use App\Models\Product;
use Illuminate\Database\Seeder;

class PrescriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Prescription::factory(random_int(1, 50))->create()
            ->map(function($p) {
                $products = Product::byActive()
                    ->inRandomOrder()
                    ->limit(random_int(1, 5))
                    ->pluck('id')
                    ->mapWithKeys(fn($product, $key) =>[$product => ['qty' => random_int(1, 10)]]);

                $p->assignProducts($products->toArray());
            });
    }
}
