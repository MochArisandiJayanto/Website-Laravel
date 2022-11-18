<?php

namespace App\Http\Controllers;

use App\Models\Pekerja;
use App\Models\User;
use Illuminate\Http\Request;
use Alert;

class PekerjaController extends Controller
{
    public function index(Request $request)
    {
        if($request->has('search')){
            $data = Pekerja::where('nama_pekerja','LIKE','%'.$request->search.'%')->get();
        }else{
            $data = Pekerja::all();
        }
        return view('pemilik.pekerja.index', compact('data'));
    }

    public function create()
    {
        return view('pemilik.pekerja.create');
    }

    public function insert(Request $request)
    {
        User::create([
            'role_id' => 2,
            'name' => $request->nama_pekerja,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        $newestUser = User::select('id')->orderBy('id', 'desc')->first();
        $newestUser = $newestUser['id'];
        Pekerja::insert([
            'user_id' => $newestUser,
            'nama_pekerja' => $request->nama_pekerja,
            'no_telp' => $request->no_telp,
            'alamat' => $request->alamat,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        Alert::success('Sukses', 'Data pekerja berhasil ditambahkan!');
        return redirect('/pekerja');
    }
}
