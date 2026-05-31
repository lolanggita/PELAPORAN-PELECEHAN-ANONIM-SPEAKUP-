<?php

namespace App\Http\Controllers;

use App\Models\KategoriKejadian;
use Illuminate\Http\Request;

class AdminKategoriController extends Controller
{
    /**
     * Tampilkan daftar semua kategori kejadian.
     */
    public function index()
    {
        $kategoris = KategoriKejadian::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.kategori.index', compact('kategoris'));
    }

    /**
     * Tampilkan form create kategori kejadian.
     */
    public function create()
    {
        return view('admin.kategori.create');
    }

    /**
     * Simpan kategori kejadian baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategori_kejadians,nama_kategori',
            'deskripsi'     => 'nullable|string|max:500',
            'is_active'     => 'nullable|boolean',
        ], [
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
            'nama_kategori.unique'   => 'Kategori dengan nama tersebut sudah ada.',
            'nama_kategori.max'      => 'Nama kategori maksimal 255 karakter.',
            'deskripsi.max'          => 'Deskripsi maksimal 500 karakter.',
        ]);

        KategoriKejadian::create([
            'nama_kategori' => $request->nama_kategori,
            'deskripsi'     => $request->deskripsi,
            'is_active'     => $request->has('is_active') ? true : false,
        ]);

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori "' . $request->nama_kategori . '" berhasil ditambahkan!');
    }
}
