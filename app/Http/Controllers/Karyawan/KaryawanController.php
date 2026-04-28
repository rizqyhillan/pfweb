<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class KaryawanController extends Controller
{
    /**
     * Dashboard karyawan (placeholder).
     */
    public function dashboard()
    {
        $karyawan = Auth::user();

        return view('karyawan.dashboard', compact('karyawan'));
    }
}
