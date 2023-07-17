<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /**
         * creation en dur d'un admin -> email: admin@admin, password: admin
         */
        User::create([
            'name' => 'admin',
            'email' => 'admin@admin',
            'password' => bcrypt('admin'),
            'role' => 'admin',
        ]);

        /**
         * creation en dur de 2 users -> email: user1@user1, password: user1 et email: user2@user2, password: user2
         */
        User::create([
            'name' => 'user1',
            'email' => 'user1@user1',
            'password' => bcrypt('user1'),
            'role' => 'user',
        ]);
        User::create([
            'name' => 'user2',
            'email' => 'user2@user2',
            'password' => bcrypt('user2'),
            'role' => 'user',
        ]);

        User::factory()
            ->count(20)
            /**
             *à decommenter si on utilise seulement le user Seeder mais qu'on veut lui attacher des restaurants/comments
             * attention chaque user créera un model et un seul on se retrouve donc
             * avec 20 user, 20 resto, 20comment associés ensembles un a un (pas un à plusieurs)
             */
            //->has(\App\Models\Comment::factory())
            //->has(\App\Models\Restaurant::factory())
            ->create();
    }
}
