<?php

namespace Database\Factories;

use App\Models\Core\Branch;
use Illuminate\Database\Eloquent\Factories\Factory;

class BranchFactory extends Factory
{
    protected $model = Branch::class;

    public function definition()
    {
        return [
            'name' => $this->faker->company . ' Branch',
            'address' => $this->faker->address,
            'mobile_phone' => $this->faker->regexify('08[1-9][0-9]{8,10}'),
            'map_link' => $this->faker->url,
            'photo' => null,
        ];
    }
}
