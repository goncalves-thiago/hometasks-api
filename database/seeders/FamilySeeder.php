<?php

namespace Database\Seeders;

use App\Models\Family;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FamilySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if(!Family::where('id', 1)->first()) {
            $superAdmin = Family::create([
                'name' => 'Família Gonçalves',
                'allowance' => 50,
                'owner_id' => 1
            ]);
        }

        if(!Family::where('id', 2)->first()) {
            $superAdmin = Family::create([
                'name' => 'Família Vargas',
                'allowance' => 20,
                'owner_id' => 4
            ]);
        }
    }
}
