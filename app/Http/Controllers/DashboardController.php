<?php

namespace App\Http\Controllers;

use App\Models\Pemilik;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Alert;
use App\Models\Pekerja;

class DashboardController extends Controller
{
    public function pemilik_dashboard()
    {
        $data_login = Auth::user()->id;
        $data = Pemilik::join('users','pemilik.user_id','=','users.id')->select('users.id','pemilik.nama_pemilik','pemilik.email','pemilik.alamat','pemilik.no_telp')->where('users.id', $data_login)->first();
        return view('pemilik.dashboard', compact('data_login','data'));
    }

    public function pemilik_akun_edit($id)
    {
        $data = Pemilik::all()->where('user_id', $id)->first();
        return view('pemilik.akun.edit', compact('data'));
    }

    public function pemilik_akun_update(Request $request, $id)
    {
        $password = $request->password;

        if (isset($password)) {
            $data_user = User::where('id', $id)->update([
                'name' => $request->nama_pemilik,
                'email' => $request->email,
                'password' => bcrypt($password)
            ]);
            $data_pemilik = Pemilik::where('user_id', $id)->update([
                'nama_pemilik' => $request->nama_pemilik,
                'email' => $request->email,
                'no_telp' => $request->no_telp,
                'alamat' => $request->alamat,
                'password' => bcrypt($password),
            ]);
        }else{
            $data_user = User::where('id', $id)->update([
                'name' => $request->nama_pemilik,
                'email' => $request->email
            ]);
            $data_pemilik = Pemilik::where('user_id', $id)->update([
                'nama_pemilik' => $request->nama_pemilik,
                'email' => $request->email,
                'no_telp' => $request->no_telp,
                'alamat' => $request->alamat
            ]);
        }
        Alert::success('Sukses', 'Data akun berhasil diubah!');
        return back();
    }

    public function pekerja_dashboard()
    {
        $data_login = Auth::user()->id;
        $data = Pekerja::join('users','pekerja.user_id','=','users.id')->select('users.id','pekerja.nama_pekerja','pekerja.email','pekerja.alamat','pekerja.no_telp')->where('users.id', $data_login)->first();
        return view('pekerja.dashboard', compact('data_login','data'));
    }

    public function pekerja_akun_edit($id)
    {
        $data = Pekerja::all()->where('user_id', $id)->first();
        return view('pekerja.akun.edit', compact('data'));
    }

    public function pekerja_akun_update(Request $request, $id)
    {
        $password = $request->password;

        if (isset($password)) {
            $data_user = User::where('id', $id)->update([
                'name' => $request->nama_pekerja,
                'email' => $request->email,
                'password' => bcrypt($password)
            ]);
            $data_pekerja = Pekerja::where('user_id', $id)->update([
                'nama_pekerja' => $request->nama_pekerja,
                'email' => $request->email,
                'no_telp' => $request->no_telp,
                'alamat' => $request->alamat,
                'password' => bcrypt($password),
            ]);
        }else{
            $data_user = User::where('id', $id)->update([
                'name' => $request->nama_pekerja,
                'email' => $request->email
            ]);
            $data_pekerja = Pekerja::where('user_id', $id)->update([
                'nama_pekerja' => $request->nama_pekerja,
                'email' => $request->email,
                'no_telp' => $request->no_telp,
                'alamat' => $request->alamat
            ]);
        }
        Alert::success('Sukses', 'Data akun berhasil diubah!');
        return back();
    }
}
