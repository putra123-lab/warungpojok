<?php

namespace App\Http\Controllers;

use App\Models\LoginActivity;
use Illuminate\Http\Request;

class LoginActivityController extends Controller
{
    public function index(Request $request)
    {
        $query = LoginActivity::with('user')
            ->latest('login_at');

        // Filter tanggal
        if ($request->filled('tanggal')) {
            $query->whereDate('login_at', $request->tanggal);
        }

        // Filter nama karyawan
        if ($request->filled('nama')) {
            $query->where('user_name', 'like', '%' . $request->nama . '%');
        }

        $activities = $query->paginate(15)->withQueryString();

        $totalLogin = LoginActivity::count();

        $totalAktif = LoginActivity::where('status', 'aktif')->count();

        $totalSelesai = LoginActivity::where('status', 'selesai')->count();

        return view('login-activities.index', compact(
            'activities',
            'totalLogin',
            'totalAktif',
            'totalSelesai'
        ));
    }
}
