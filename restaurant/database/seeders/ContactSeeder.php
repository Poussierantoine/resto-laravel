<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{


    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = \App\Models\User::find(2);
        if ($user) {
            \App\Models\Contact::create([
                'name' => $user->name,
                'email' => $user->email,
                'message' => "A l'aide, je ne sais pas comment utiliser ce site",
            ]);
        }



        \App\Models\Contact::factory(4)->create();
    }
}
