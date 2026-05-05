<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Article;
use App\Models\ArticleVariant;
use Illuminate\Support\Str;

class ArticleVariantSeeder extends Seeder
{
    public function run(): void
    {
        $articles = Article::all();

        foreach ($articles as $article) {

            if (!$article->attributes) {
                continue; // no variants
            }

            $attributes = $article->attributes;

            // generate combinations
            $combinations = $this->generateCombinations($attributes);

            foreach ($combinations as $index => $combo) {

                ArticleVariant::create([
                    'article_id' => $article->id,
                    'sku' => strtoupper(Str::random(10)),
                    'attributes' => $combo,
                    'label' => implode(' - ', $combo),
                    'default_price' => fake()->randomFloat(2, 50, 500),
                    'cost_price' => fake()->randomFloat(2, 20, 300),
                    'image' => $article->image,
                    'sort_order' => $index,
                    'is_active' => true,
                ]);
            }
        }
    }

    private function generateCombinations($arrays)
    {
        $result = [[]];

        foreach ($arrays as $key => $values) {
            $tmp = [];

            foreach ($result as $res) {
                foreach ($values as $value) {
                    $tmp[] = array_merge($res, [$key => $value]);
                }
            }

            $result = $tmp;
        }

        return $result;
    }
}