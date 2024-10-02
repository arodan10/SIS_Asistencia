<?php

namespace Database\Seeders;

use App\Models\Member;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->call(UserSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(ChurchSeeder::class);
        $this->call(PostSeeder::class);

        $this->call(DimensionSeeder::class);
        $this->call(IndicatorSeeder::class);
        $this->call(GroupSeeder::class);

        $this->call(MemberSeeder::class);
    }
}
