<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            'cheap', 'good_critics',
            'expensive', 'burger_adict',
            'pizza_adict', 'our_choice',
            'environement_frendly', 'good_team',
            'bar_restaurant'
        ];

        $dbTags = Tag::all()->pluck('name')->toArray();

        $tags = array_diff($tags, $dbTags);

        if (empty($tags)) {
            Tag::factory()->count(10)->create();
        } else {
            foreach ($tags as $tag) {
                Tag::create([
                    'name' => $tag,
                    'active' => true,
                ]);
            }
        }
    }
}
