<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use App\Models\ProdukDibuat;
use Illuminate\Http\Request;
use Alert;
use App\Models\Laporan;
use App\Models\ProdukTerjual;
use Illuminate\Support\Facades\Redis;

class ProdukController extends Controller
{
    public function index()
    {
        return view('all.produk.index');
    }

    public function produk_dibuat(Request $request)
    {
        if ($request->has('search')) {
            $data = ProdukDibuat::where('nama_produk', 'LIKE', '%' . $request->search . '%')->get();
        } else {
            $data = ProdukDibuat::all();
        }
        return view('all.produk.dibuat.index', compact('data'));
    }

    public function produk_dibuat_create()
    {
        $data = BahanBaku::all();
        return view('all.produk.dibuat.create', compact('data'));
    }

    public function produk_dibuat_insert(Request $request)
    {
        $limit = BahanBaku::select('id', 'jumlah_bahanbaku')->where('id', $request->bahanbaku_id)->first();
        $limit = $limit['jumlah_bahanbaku'];

        if ($request->jumlah_bahanbaku < $limit) {
            ProdukDibuat::create([
                'bahanbaku_id' => $request->bahanbaku_id,
                'tanggal' => $request->tanggal,
                'nama_produk' => $request->nama_produk,
                'jumlah_bahanbaku' => $request->jumlah_bahanbaku,
                'jumlah_produk_dibuat' => $request->jumlah_produk_dibuat
            ]);

            $bahanbaku = BahanBaku::where('id', $request->bahanbaku_id)->update([
                'jumlah_bahanbaku' => intval($limit) - intval($request->jumlah_bahanbaku)
            ]);

            $produk_dibuat_id = ProdukDibuat::select('id')->orderBy('id', 'desc')->first();
            $produk_dibuat_id = $produk_dibuat_id['id'];
            Laporan::create([
                'bahanbaku_id' => $request->bahanbaku_id,
                'produk_dibuat_id' => $produk_dibuat_id,
                'tanggal' => $request->tanggal,
                'jumlah_pemesanan' => $request->jumlah_bahanbaku,
                'jumlah_produk_dibuat' => $request->jumlah_produk_dibuat
            ]);

            Alert::success('Sukses', 'Data produk berhasil ditambahkan!');
            return redirect('/produk/dibuat');
        } else {
            Alert::Error('Terjadi Kesalahan', 'Data permintaan Bahan Baku melebihi kapasitas yang tersedia!');
            return back();
        }
    }

    public function produk_dibuat_edit($id)
    {
        $data = ProdukDibuat::where('id', $id)->first();
        return view('all.produk.dibuat.edit', compact('data'));
    }

    public function produk_dibuat_update(Request $request, $id)
    {
        $data = ProdukDibuat::where('id', $id)->update([
            'tanggal' => $request->tanggal,
            'nama_produk' => $request->nama_produk,
            'jumlah_produk_dibuat' => $request->jumlah_produk_dibuat
        ]);
        Alert::success('Sukses', 'Data produk berhasil diubah!');
        return redirect('/produk/dibuat');
    }

    public function produk_terjual(Request $request)
    {
        if ($request->has('search')) {
            $data = ProdukTerjual::where('nama_produk', 'LIKE', '%' . $request->search . '%')->get();
        } else {
            $data = ProdukTerjual::all();
        }
        return view('all.produk.terjual.index', compact('data'));
    }

    public function produk_terjual_create()
    {
        return view('all.produk.terjual.create');
    }

    public function produk_terjual_insert(Request $request)
    {
        ProdukTerjual::create([
            'tanggal' => $request->tanggal,
            'nama_produk' => $request->nama_produk,
            'jumlah_produk' => $request->jumlah_produk,
            'harga_produk' => $request->harga_produk,
            'total' => intval($request->jumlah_produk) * intval($request->harga_produk)
        ]);
        $produk_terjual_id = ProdukTerjual::select('id')->orderBy('id', 'desc')->first();
        $produk_terjual_id = $produk_terjual_id['id'];
        Laporan::create([
            'produk_terjual_id' => $produk_terjual_id,
            'tanggal' => $request->tanggal,
            'jumlah_produk_terjual' => $request->jumlah_produk
        ]);

        Alert::success('Sukses', 'Data penjualan produk berhasil ditambahkan!');
        return redirect('/produk/terjual');
    }

    public function produk_terjual_edit($id)
    {
        $data = ProdukTerjual::where('id', $id)->first();
        return view('all.produk.terjual.edit', compact('data'));
    }

    public function produk_terjual_update(Request $request, $id)
    {
        $data = ProdukTerjual::where('id', $id)->update([
            'tanggal' => $request->tanggal,
            'nama_produk' => $request->nama_produk,
            'jumlah_produk' => $request->jumlah_produk,
            'harga_produk' => $request->harga_produk,
            'total' => intval($request->jumlah_produk) * intval($request->harga_produk)
        ]);
        Alert::success('Sukses', 'Data penjualan produk berhasil diubah!');
        return redirect('/produk/terjual');
    }
}
