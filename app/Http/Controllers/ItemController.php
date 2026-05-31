<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    // Fungsi untuk menampilkan daftar barang di halaman utama
    public function index()
    {
        $items = Item::with('user')->orderBy('created_at', 'desc')->get();
        return view('items.index', compact('items'));
    }

    // Fungsi untuk menampilkan form pembuatan laporan
    public function create()
    {
        return view('items.create');
    }

    // Fungsi untuk memproses data dari form ke database
    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'lokasi_ditemukan_atau_hilang' => 'required|string|max:255',
            'status' => 'required|in:hilang,ditemukan',
        ]);

        Item::create([
            'user_id' => Auth::id(), // Mengambil ID dari mahasiswa yang sedang login
            'nama_barang' => $request->nama_barang,
            'deskripsi' => $request->deskripsi,
            'lokasi_ditemukan_atau_hilang' => $request->lokasi_ditemukan_atau_hilang,
            'status' => $request->status,
        ]);

        return redirect()->route('items.index')->with('success', 'Laporan berhasil dibuat!');
    }

    // Fungsi untuk melihat detail barang dan pengajuan klaim
    public function show(Item $item)
    {
        // Mengambil data barang, pelapornya, dan semua klaim beserta data pengklaim
        $item->load(['user', 'claims.user']);

        return view('items.show', compact('item'));
    }
}
