<?php

namespace App\Http\Controllers;

use App\Models\Keuangan;
use App\Models\Pemilik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Alert;

class KeuanganController extends Controller
{
    public function index()
    {
        $data = Keuangan::select('id','tanggal','pengeluaran','pendapatan')->orderBy('tanggal','desc')->get();
        return view('pemilik.keuangan.index', compact('data'));
    }

    public function create()
    {
        return view('pemilik.keuangan.create');
    }

    public function insert(Request $request)
    {
        $pemilik_id = Pemilik::select('id','user_id')->where('user_id', Auth::user()->id)->first();
        $pemilik_id = $pemilik_id['id'];
        Keuangan::create([
            'pemilik_id' => $pemilik_id,
            'tanggal' => $request->tanggal,
            'pengeluaran' => $request->pengeluaran,
            'pendapatan' => $request->pendapatan
        ]);
        Alert::success('Sukses', 'Data keuangan berhasil ditambahkan!');
        return redirect('/keuangan');
    }

    public function edit($id)
    {
        $data = Keuangan::where('id', $id)->first();
        return view('pemilik.keuangan.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $data = Keuangan::where('id', $id)->update([
            'tanggal' => $request->tanggal,
            'pengeluaran' => $request->pengeluaran,
            'pendapatan' => $request->pendapatan
        ]);
        Alert::success('Sukses', 'Data keuangan berhasil diubah!');
        return redirect('/keuangan');
    }
}
