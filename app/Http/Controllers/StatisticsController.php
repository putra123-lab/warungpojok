<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    public function index()
    {
        $penjualanHariIni = 0;
        $transaksiHariIni = 0;
        $totalProduk = 0;
        $stokMenipis = 0;

        /*
         * Data transaksi dan produk perlu disesuaikan
         * dengan nama tabel serta kolom di database project.
         */

        return view('statistics.index', compact(
            'penjualanHariIni',
            'transaksiHariIni',
            'totalProduk',
            'stokMenipis'
        ));
    }
}
