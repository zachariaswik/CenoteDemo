<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Truncate tables to avoid unique constraint violations on re-runs
        DB::table('articles')->truncate();
        DB::table('categories')->truncate();

        // Create 3-5 categories
        $categories = Category::factory(fake()->numberBetween(3, 5))->create();

        // Create 3-8 authors (users)
        $authors = User::factory(fake()->numberBetween(3, 8))->create();

        // Create 20 articles
        Article::factory(20)->make()->each(function (Article $article) use ($categories, $authors) {
            $article->category_id = $categories->random()->id;
            $article->author_id = $authors->random()->id;
            $article->save();
        });
    }
}
