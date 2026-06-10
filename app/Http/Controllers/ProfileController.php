<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman form edit profil
     */
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    /**
     * Memproses pembaruan data profil
     */
    public function update(Request $request)
    {
        // Menggunakan User::find() agar VS Code dan Laravel mengenali objek
        // sebagai Eloquent Model yang memiliki method save()
        $user = User::find(Auth::id());

        // Validasi input dari form
        $request->validate([
            'no_whatsapp' => 'required|string|max:15',
            'password' => 'nullable|string|min:8|confirmed', 
        ]);

        // Memperbarui nomor WhatsApp
        $user->no_whatsapp = $request->no_whatsapp;

        // Jika kolom password baru diisi oleh mahasiswa
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // Menyimpan perubahan ke database MySQL
        $user->save();

        return redirect()->route('profile.edit')->with('success', 'Profil Anda berhasil diperbarui!');
    }
}
