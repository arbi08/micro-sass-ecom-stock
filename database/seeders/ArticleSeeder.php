<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $categories = Category::all();

        $colors = ['black', 'white', 'red', 'blue'];
        $sizes  = ['S', 'M', 'L'];

        for ($i = 1; $i <= 200; $i++) {

            $category = $categories->random();

            $name = $faker->words(3, true);

            $hasVariants = $faker->boolean(70);

            $attributes = null;

            if ($hasVariants) {
                $attributes = [
                    'color' => $faker->randomElements($colors, rand(2, 4)),
                    'size'  => $faker->randomElements($sizes, rand(2, 3)),
                ];
            }

            Article::create([
                'name' => ucfirst($name),
                'slug' => Str::slug($name . '-' . $i),
                'description' => $faker->paragraph(),
                'category_id' => $category->id,
                'brand' => $faker->company(),
                'image' => "https://picsum.photos/seed/article{$i}/600/600",
                'images' => json_encode([
                    "https://picsum.photos/seed/article{$i}-1/600/600",
                    "https://picsum.photos/seed/article{$i}-2/600/600"
                ]),
                'attributes' => $attributes,
                'is_active' => true,
                'is_featured' => $faker->boolean(20),
                'sort_order' => $i,
            ]);
        }
    }
}