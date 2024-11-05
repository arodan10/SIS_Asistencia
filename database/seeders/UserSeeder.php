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
        ])->assignRole('Super-admin');

        $users = User::factory()->count(3)->create();

        foreach ($users as $user) {
            $user->assignRole('Presidente');
        }
    }
}
