<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class NewsModuleSeeder extends Seeder
{
    /**
     * Run the news module seeders in correct order
     */
    public function run()
    {
        $this->command->info('Seeding News Module Data...');
        
        $this->call([
            NewsCategorySeeder::class,
            MediaFileSeeder::class,
            NewsSeeder::class,
            NewsCommentSeeder::class,
            NewsUserActionSeeder::class,
        ]);
        
        $this->command->info('News Module seeding completed successfully!');
    }
}
