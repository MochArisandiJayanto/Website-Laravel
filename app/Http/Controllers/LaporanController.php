<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use Illuminate\Http\Request;
use Alert;
use App\Models\ProdukDibuat;

class LaporanController extends Controller
{
    public function index()
    {
        $data = Laporan::select('id','tanggal','jumlah_pemesanan','jumlah_produk_dibuat','jumlah_produk_tersisa','jumlah_produk_terjual')->orderBy('id','desc')->get();
        return view('all.laporan.index', compact('data'));
    }

    public function create()
    {
        $data = ProdukDibuat::all();

        return view('all.laporan.create', compact('data'));
    }

    public function insert(Request $request)
    {
        $data = ProdukDibuat::select('nama_produk', 'bahanbaku_id', 'jumlah_bahanbaku', 'jumlah_produk_dibuat')->where('id', $request->produk_dibuat_id)->first();
        $bahanbaku_id = $data['bahanbaku_id'];
        $jumlah_bahanbaku = $data['jumlah_bahanbaku'];
        $jumlah_produk_dibuat = $data['jumlah_produk_dibuat'];

        if ($request->jumlah_produk_terjual > $jumlah_produk_dibuat) {
            Alert::error('Terjadi Kesalahan', 'Jumlah Produk Terjual melebihi kapasitas pada data yang telah ada!');
            return back();
        }elseif($request->jumlah_pemesanan > $jumlah_bahanbaku){
            Alert::error('Terjadi Kesalahan', 'Jumlah Pemesanan Bahan Baku melebihi kapasitas pada data yang telah ada!');
            return back();
        }else{

            Laporan::create([
                'bahanbaku_id' => $bahanbaku_id,
                'produk_dibuat_id' => $request->produk_dibuat_id,
                'tanggal' => $request->tanggal,
                'jumlah_pemesanan' => $request->jumlah_pemesanan,
                'jumlah_produk_dibuat' => $request->jumlah_produk_dibuat,
                'jumlah_produk_tersisa' => $request->jumlah_produk_tersisa,
                'jumlah_produk_terjual' => $request->jumlah_produk_terjual
            ]);
            Alert::success('Sukses', 'Data laporan berhasil ditambah!');
            return redirect('/laporan');
        }
    }

    public function edit($id)
    {
        $data = Laporan::findorfail($id);
        return view('all.laporan.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $data = Laporan::where('id', $id)->update([
            'tanggal' => $request->tanggal,
            'jumlah_pemesanan' => $request->jumlah_pemesanan,
            'jumlah_produk_dibuat' => $request->jumlah_produk_dibuat,
            'jumlah_produk_tersisa' => $request->jumlah_produk_tersisa,
            'jumlah_produk_terjual' => $request->jumlah_produk_terjual
        ]);
        Alert::success('Sukses', 'Data laporan berhasil diubah!');
        return redirect('/laporan');
    }
}
