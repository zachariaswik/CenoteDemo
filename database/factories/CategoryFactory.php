<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        static $usedWords = [];
        
        do {
            $name = $this->faker->word();
        } while (in_array($name, $usedWords));
        
        $usedWords[] = $name;

        return [
            'name' => ucfirst($name),
            'slug' => $name,
        ];
    }
}
