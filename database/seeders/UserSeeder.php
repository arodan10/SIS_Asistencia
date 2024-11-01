<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name'=>'Dynamus Developer Team',
            'email'=>'dynamus@gmail.com',
            'password'=>bcrypt('12345678'),
        ]);
        User::factory()->count(3)->create();
    }
}
