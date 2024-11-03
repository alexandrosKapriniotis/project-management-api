<?php

namespace Database\Factories;

use App\Enums\ProjectType;
use App\Models\Company;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    protected $model = Project::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph,
            'type' => $this->faker->randomElement([ProjectType::Standard->value, ProjectType::Complex->value]),
            'company_id' => Company::factory(),
            'budget' => $this->faker->randomFloat(2, 10000, 100000),
            'timeline' => $this->faker->date,
        ];
    }
}
