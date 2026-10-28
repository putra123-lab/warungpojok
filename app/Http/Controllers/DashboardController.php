<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        $hariIni = now()->toDateString();

        // Nilai awal dashboard
        $penjualanHariIni = 0;
        $totalTransaksiHariIni = 0;
        $totalProduk = 0;
        $jumlahPengguna = 0;
        $stokMenipis = 0;
        $transaksiTerbaru = collect();
        $dataGrafik = collect();

        // Data penjualan
        if (Schema::hasTable('sales')) {
            $kolomTotal = null;

            foreach (['total', 'total_price', 'total_amount', 'grand_total'] as $kolom) {
                if (Schema::hasColumn('sales', $kolom)) {
                    $kolomTotal = $kolom;
                    break;
                }
            }

            $queryHariIni = DB::table('sales');

            if (Schema::hasColumn('sales', 'created_at')) {
                $queryHariIni->whereDate('created_at', $hariIni);
            }

            $totalTransaksiHariIni = (clone $queryHariIni)->count();

            if ($kolomTotal !== null) {
                $penjualanHariIni = (clone $queryHariIni)->sum($kolomTotal);
            }

            $kolomUrutan = Schema::hasColumn('sales', 'created_at')
                ? 'created_at'
                : 'id';

            $transaksiTerbaru = DB::table('sales')
                ->orderByDesc($kolomUrutan)
                ->limit(5)
                ->get();
        }

        // Data produk
        if (Schema::hasTable('products')) {
            $totalProduk = DB::table('products')->count();

            $kolomStok = null;

            foreach (['stock', 'stock_quantity', 'quantity'] as $kolom) {
                if (Schema::hasColumn('products', $kolom)) {
                    $kolomStok = $kolom;
                    break;
                }
            }

            // Menghitung jumlah produk dengan stok maksimal 5
            if ($kolomStok !== null) {
                $stokMenipis = DB::table('products')
                    ->where($kolomStok, '<=', 5)
                    ->count();
            }
        }

        // Data pengguna
        if (Schema::hasTable('users')) {
            $jumlahPengguna = DB::table('users')->count();
        }

        // Kirim semua variabel ke halaman dashboard
        return view('dashboard', compact(
            'penjualanHariIni',
            'totalTransaksiHariIni',
            'totalProduk',
            'jumlahPengguna',
            'stokMenipis',
            'transaksiTerbaru',
            'dataGrafik'
        ));
    }
}
