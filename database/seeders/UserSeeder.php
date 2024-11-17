<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if(!User::where('email', 'thiago@gmail.com')->first()) {
            $superAdmin = User::create([
                'name' => 'Thiago Gonçalves',
                'email' => 'thiago@gmail.com',
                'password' => Hash::make('123456', ['rounds' => 12])
            ]);
        }

        if(!User::where('email', 'dado@gmail.com')->first()) {
            $superAdmin = User::create([
                'name' => 'Ricardo Gonçalves',
                'email' => 'dado@gmail.com',
                'password' => Hash::make('123456', ['rounds' => 12])
            ]);
        }

        if(!User::where('email', 'fefe@gmail.com')->first()) {
            $superAdmin = User::create([
                'name' => 'Fernando Gonçalves',
                'email' => 'fefe@gmail.com',
                'password' => Hash::make('123456', ['rounds' => 12])
            ]);
        }

        // if(!User::where('email', 'grazivargas@gmail.com')->first()) {
        //     $superAdmin = User::create([
        //         'name' => 'Graziele Vargas',
        //         'email' => 'grazivargas@gmail.com',
        //         'password' => Hash::make('123456', ['rounds' => 12])
        //     ]);
        // }

        // if(!User::where('email', 'enzovargas@gmail.com')->first()) {
        //     $superAdmin = User::create([
        //         'name' => 'Enzo Vargas',
        //         'email' => 'enzovargas@gmail.com',
        //         'password' => Hash::make('123456', ['rounds' => 12])
        //     ]);
        // }
    }
}
