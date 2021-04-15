<?php

namespace Database\Factories;

use App\Models\Prescription;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PrescriptionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Prescription::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "doctor_id" => User::onlyDoctors()->byActive()->inRandomOrder()->first()->id,
            "pharmacist_id" => User::onlyDoctors()->byActive()->inRandomOrder()->first()->id,
            "patient_id" => User::onlyDoctors()->byActive()->inRandomOrder()->first()->id,
            "notes" => $this->faker->text,
            "status" => Prescription::getStatusId('*')->random(1)->first(),
        ];
    }
}
