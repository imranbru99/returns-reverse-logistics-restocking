<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\CmsPage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CmsPage>
 */
class CmsPageFactory extends Factory
{
    protected $model = CmsPage::class;

    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'body' => fake()->paragraph(),
            'published_at' => now(),
        ];
    }
}
