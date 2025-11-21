<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\News;
use Illuminate\Support\Facades\File;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing news
        News::truncate();
        
        // Read JSON file
        $newsPath = public_path('data/news.json');
        if (!File::exists($newsPath)) {
            $this->command->error('News JSON file not found!');
            return;
        }
        
        $newsData = json_decode(File::get($newsPath), true);
        
        // Insert into database
        foreach ($newsData as $item) {
            News::create([
                'title' => $item['title'],
                'slug' => $item['slug'],
                'excerpt' => $item['excerpt'] ?? null,
                'content' => $item['content'],
                'image' => $item['image'] ?? null,
                'category' => $item['category'] ?? null,
                'views' => $item['views'] ?? 0,
                'published_at' => $item['published_at'] ?? now(),
                'author' => $item['author'] ?? 'Admin',
            ]);
        }
        
        $this->command->info('Successfully imported ' . count($newsData) . ' news articles!');
    }
}
