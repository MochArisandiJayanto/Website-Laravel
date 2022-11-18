<?php

namespace App\Http\Controllers;

use App\Models\Keuangan;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class GrafikController extends Controller
{
    public function index()
    {
        $total_pendapatan = Keuangan::select(DB::raw("CAST(SUM(pendapatan) as int) as total_pendapatan"))->GroupBy(DB::raw("Month(tanggal)"))->pluck('total_pendapatan');

        $total_pengeluaran = Keuangan::select(DB::raw("CAST(SUM(pengeluaran) as int) as total_pengeluaran"))->GroupBy(DB::raw("Month(tanggal)"))->pluck('total_pengeluaran');
        
        $bulan = Keuangan::select(DB::raw("MONTHNAME(tanggal) as bulan"))->GroupBy(DB::raw("MONTHNAME(tanggal)"))->pluck('bulan');

        return view('all.grafik.index', compact('total_pendapatan', 'total_pengeluaran','bulan'));
    }
}
