<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Claim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // Mengambil semua barang yang dilaporkan oleh user yang sedang login
        $laporanSaya = Item::where('user_id', $userId)
                            ->orderBy('created_at', 'desc')
                            ->get();

        // Mengambil semua klaim yang diajukan oleh user yang sedang login beserta data barangnya
        $klaimSaya = Claim::with('item')
                          ->where('user_id', $userId)
                          ->orderBy('created_at', 'desc')
                          ->get();

        return view('dashboard', compact('laporanSaya', 'klaimSaya'));
    }
}
