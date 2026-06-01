<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $mahasiswa = [
            [
                'name' => 'Henry Glenn Iksan',
                'email' => 'henry@mhs.mdp.ac.id',
                'npm' => '2428240002',
                'no_whatsapp' => '081100001111',
                'is_admin' => true,
                'password' => Hash::make('admin123')
            ],
              [
                'name' => 'Henry 2',
                'email' => 'henry2@mhs.mdp.ac.id',
                'npm' => '2428240001',
                'no_whatsapp' => '081100001111',
                'is_admin' => false,
                'password' => Hash::make('admin123')
            ],

        ];

        foreach ($mahasiswa as $mhs) {
            User::create([
                'name' => $mhs['name'],
                'email' => $mhs['email'],
                'npm' => $mhs['npm'],
                'password' => $mhs['password'],
                'no_whatsapp' => $mhs['no_whatsapp'],
                'is_admin' => $mhs['is_admin'] ?? false
            ]);
        }
    }
}
