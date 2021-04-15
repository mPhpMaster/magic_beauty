<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'category_id' => ($category = Category::byActive()->inRandomOrder()->first())->id,
            'branch_id' => $category->branch_id,
            'name' => $this->faker->unique()->name,
            'description' => $this->faker->text,
            'price' => $this->faker->numberBetween(10, 1000),
            'qty' => $this->faker->numberBetween(1, 100),
            'status' => 'active',
//            'status' => Product::getStatusId('*')->random(1)->first(),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
            ];
        });
    }
}
