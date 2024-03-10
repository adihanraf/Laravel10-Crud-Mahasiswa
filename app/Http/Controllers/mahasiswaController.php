<?php

namespace App\Http\Controllers;

use App\Models\mahasiswa;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class mahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $katakunci = $request->katakunci;
        $jumlahbaris = 4;
        if(strlen($katakunci)){
            $data = mahasiswa::where('nim','like',"%$katakunci%")
                    ->orWhere('nama','like',"%$katakunci%")
                    ->orWhere('jurusan','like',"%$katakunci%")
                    ->paginate($jumlahbaris);
        } else {
            $data = mahasiswa::orderBy('nim', 'desc')->paginate($jumlahbaris);
        }

        return view('mahasiswa.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('mahasiswa.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Session::flash('nim',$request->nim);
        Session::flash('nama',$request->nama);
        Session::flash('jurusan',$request->jurusan);

        $request->validate([
            'nim' => 'required|numeric|unique:mahasiswas,nim',
            'nama' => 'required',
            'jurusan' => 'required',
        ], [
            'nim.required' => 'Nim wajib di isi',
            'nim.numeric' => 'Nim wajib dalam angka',
            'nim.unique' => 'Nim yang di isi sudah di gunakan',
            'nama.required' => 'Nama wajib di isi',
            'jurusan.required' => 'Jurusan wajib di isi',
        ]);

        $data = [
            'nim' => $request->nim,
            'nama' => $request->nama,
            'jurusan' => $request->jurusan,
        ];

        mahasiswa::create($data);

        return redirect()->to('mahasiswa')->with('success', 'Data berhasil ditambah');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = mahasiswa::where('nim',$id)->first();
        return view('mahasiswa.edit')->with('data', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama' => 'required',
            'jurusan' => 'required',
        ], [
            'nama.required' => 'Nama wajib di isi',
            'jurusan.required' => 'Jurusan wajib di isi',
        ]);

        $data = [
            'nama' => $request->nama,
            'jurusan' => $request->jurusan,
        ];

        mahasiswa::where('nim',$id)->update($data);

        return redirect()->to('mahasiswa')->with('success', 'Data berhasil di Update');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        mahasiswa::where('nim', $id)->delete();
        return redirect()->to('mahasiswa')->with('success', 'data berhasil di hapus');
    }


}
