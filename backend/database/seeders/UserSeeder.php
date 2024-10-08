<?php

namespace Database\Seeders;

use App\Models\User;
use Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!User::where("name", "Teste01")->first()) {
            User::create([
                "name" => "Teste01",
                "email" => "teste1@email.com",
                "password" => Hash::make("123456a!", ['rounds' => 12]),
            ]);
        }

        if (!User::where("name", "Teste02")->first()) {
            User::create([
                "name" => "Teste02",
                "email" => "teste2@email.com",
                "password" => Hash::make("123456a!", ['rounds' => 12]),
            ]);
        }
    }
}
