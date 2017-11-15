<?php

use App\Note;
use App\User;
use App\Panel;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Note::truncate();
        Panel::truncate();
        User::truncate();

        factory(User::class, 1)->create();
        factory(Panel::class, 15)->create();
        factory(Note::class, 100)->create();
    }
}
