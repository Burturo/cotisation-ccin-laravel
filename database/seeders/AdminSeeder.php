<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Utilisateur;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        if (!Utilisateur::where('role', 'admin')->exists()) {
            Utilisateur::create([
                'username' => 'admin',
                'password' => Hash::make('admin123'),
                'firstname' => 'Super',
                'lastname' => 'Admin',
                'gender' => 'F',
                'phone' => '80638300',
                'address' => 'Administration centrale',
                'role' => 'admin',
                'image' => null, 
                'first_login' => false, 
            ]);
        }
    }
}
