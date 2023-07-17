<?php

namespace Database\Seeders;

use App\Models\Comment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Comment::create([
            'user_id' => 2,
            'restaurant_id' => 1,
            'content' => "Un très bon restaurant, je recommande !
            C'est le mien mais je reste extremement objectif ! Venez vite on est au bord de la faillite ...",
        ]);

        Comment::factory(10)->create();
    }
}
