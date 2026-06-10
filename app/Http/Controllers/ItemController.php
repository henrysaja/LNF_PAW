<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $query = Item::with('user')->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $cari = $request->search;
            $query->where(function ($q) use ($cari) {
                $q->where('nama_barang', 'like', '%' . $cari . '%')
                    ->orWhere('lokasi_ditemukan_atau_hilang', 'like', '%' . $cari . '%')
                    ->orWhere('deskripsi', 'like', '%' . $cari . '%');
            });
        }

        if ($request->filled('status') && $request->status !== 'semua') {
            $query->where('status', $request->status);
        }

        $items = $query->get();

        if ($request->ajax()) {
            return view('items.partials._item_list', compact('items'))->render();
        }

        return view('items.index', compact('items'));
    }

    public function create()
    {
        return view('items.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'lokasi_ditemukan_atau_hilang' => 'required|string|max:255',
            'status' => 'required|in:hilang,ditemukan',
            'foto_barang' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $imagePath = $request->hasFile('foto_barang')
            ? $request->file('foto_barang')->store('items', 'public')
            : null;

        Item::create([
            'user_id' => Auth::id(),
            'nama_barang' => $request->nama_barang,
            'deskripsi' => $request->deskripsi,
            'lokasi_ditemukan_atau_hilang' => $request->lokasi_ditemukan_atau_hilang,
            'status' => $request->status,
            'foto_barang' => $imagePath,
        ]);

        return redirect()->route('items.index')->with('success', 'Laporan berhasil dibuat!');
    }

    public function show(Item $item)
    {
        $item->load(['user', 'claims.user']);
        return view('items.show', compact('item'));
    }

    public function markAsFound(Item $item)
    {
        if ($item->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        if ($item->status !== 'hilang') {
            return redirect()->back()->with('error', 'Laporan ini sudah tidak berstatus hilang.');
        }

        $item->update(['status' => 'ditemukan']);

        return redirect()->back()->with('success', 'Status laporan berhasil diperbarui!');
    }

    /**
     * Membatalkan laporan dengan mengubah status menjadi 'dibatalkan'
     */
    public function cancel($id)
    {
        $item = Item::find($id);

        if (!$item) {
            return redirect()->back()->with('error', 'Laporan tidak ditemukan.');
        }

        if ($item->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk membatalkan laporan ini.');
        }

        // Karena status sekarang VARCHAR, kode ini tidak akan error lagi
        $item->update(['status' => 'dibatalkan']);

        return redirect()->route('dashboard')->with('success', 'Laporan berhasil dibatalkan.');
    }
}
