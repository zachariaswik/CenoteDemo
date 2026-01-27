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
        $categoryCount = rand(3, 5);
        $categories = Category::factory($categoryCount)->create();

        // Create 3-8 authors (users)
        $authorCount = rand(3, 8);
        $authors = User::factory($authorCount)->create();

        // Create 20 published articles with proper relationships
        for ($i = 0; $i < 20; $i++) {
            Article::factory()
                ->published()
                ->create([
                    'category_id' => $categories->random()->id,
                    'author_id' => $authors->random()->id,
                ]);
        }
    }
}
