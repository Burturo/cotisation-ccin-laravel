<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insert([
            ['nom' => 'Admin', 'description' => 'Gère tout le système'],
            ['nom' => 'Caissier', 'description' => 'Gère les paiements'],
            ['nom' => 'Financier', 'description' => 'Gère les cotisations et les ressortissants'],
        ]);
    }
}
