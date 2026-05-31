<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Menambahkan data NPM ke dalam array dummy mahasiswa
        $mahasiswa = [
            [
                'name' => 'Henry Glenn Iksan',
                'email' => 'henry@mhs.mdp.ac.id',
                'npm' => '2428240002',
                'no_whatsapp' => '081100001111'
            ],
            [
                'name' => 'Severiano Firmansya',
                'email' => 'severiano@mhs.mdp.ac.id',
                'npm' => '2428240003',
                'no_whatsapp' => '081100002222'
            ],
            [
                'name' => 'Margareth Beautrice Laurentia',
                'email' => 'margareth@mhs.mdp.ac.id',
                'npm' => '2428240004',
                'no_whatsapp' => '081100003333'
            ]
        ];

        foreach ($mahasiswa as $mhs) {
            User::create([
                'name' => $mhs['name'],
                'email' => $mhs['email'],
                'npm' => $mhs['npm'],
                'password' => Hash::make('rahasia123'), // Password default untuk semua: rahasia123
                'no_whatsapp' => $mhs['no_whatsapp']
            ]);
        }
    }
}
