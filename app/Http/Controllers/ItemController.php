<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Tambahkan baris ini

class ItemController extends Controller
{
    // ... fungsi index dan create ...

    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'lokasi_ditemukan_atau_hilang' => 'required|string|max:255',
            'status' => 'required|in:hilang,ditemukan',
        ]);

        Item::create([
            'user_id' => Auth::id(), // Menggunakan Facade Auth
            'nama_barang' => $request->nama_barang,
            'deskripsi' => $request->deskripsi,
            'lokasi_ditemukan_atau_hilang' => $request->lokasi_ditemukan_atau_hilang,
            'status' => $request->status,
        ]);

        return redirect()->route('items.index')->with('success', 'Laporan berhasil dibuat!');
    }
}
