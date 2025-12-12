<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AreaLineFactory extends Factory
{
    public function definition()
    {
        // Ensure folder exists
        $imagePath = storage_path('app/public/area');
        if (!file_exists($imagePath)) {
            mkdir($imagePath, 0777, true);
        }

        return [
            'area_name'   => $this->faker->randomElement(['Chennai', 'Coimbatore', 'Madurai', 'Trichy']),

            // Generate image & store only the filename
            'image' => $this->faker->image(
                $imagePath,   // save to folder
                640,          // width
                480,          // height
                null,         // category
                false         // FALSE = return filename instead of full path
            ),

            'route_name'  => 'Route ' . $this->faker->randomDigitNotZero(),
            'start_point' => $this->faker->streetName(),
            'end_point'   => $this->faker->streetName(),
            'distance_km' => $this->faker->numberBetween(3, 40),
            'priority'    => $this->faker->numberBetween(1, 10),
            'status'      => $this->faker->boolean(90),
        ];
    }
}
