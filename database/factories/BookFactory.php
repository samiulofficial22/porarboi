<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_id' => Category::inRandomOrder()->first()?->id ?? Category::factory(),
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(4),
            'price' => $this->faker->randomFloat(2, 10, 100),
            'cover_image' => 'covers/dummy.jpg',
            'pdf_file' => 'books/dummy.pdf',
            'is_active' => true,
        ];
    }
}
