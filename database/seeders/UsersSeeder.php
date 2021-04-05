<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Note;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        User::factory()
            ->times(10)
            ->has(Note::factory()->count(10))
            ->create();

        foreach (Note::all() as $note) {
            $note->categories()->attach(rand(1,5));
        }

    }
}
