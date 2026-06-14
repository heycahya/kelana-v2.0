<?php

namespace App\Http\Controllers\AdminWeb;

use App\Http\Controllers\Controller;
use App\Models\PaketWisata;
use Illuminate\Http\Request;

class PaketWebController extends Controller
{
    public function index(Request $request)
    {
        $query = PaketWisata::query();

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_paket', 'like', "%{$search}%")
                  ->orWhere('rute', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        $pakets = $query->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.paket.index', compact('pakets'));
    }

    public function create()
    {
        return view('admin.paket.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_paket' => 'required|string|max:150',
            'deskripsi' => 'required|string',
            'harga' => 'required|numeric|min:0',
            'rute' => 'required|string',
            'fasilitas' => 'required|string',
            'latitude' => 'nullable|string',
            'longitude' => 'nullable|string',
        ]);

        PaketWisata::create($validated);

        return redirect()->route('admin.paket.index')->with('success', 'Paket Wisata berhasil dibuat!');
    }

    public function show($id)
    {
        $paket = PaketWisata::findOrFail($id);
        return view('admin.paket.show', compact('paket'));
    }

    public function edit($id)
    {
        $paket = PaketWisata::findOrFail($id);
        return view('admin.paket.edit', compact('paket'));
    }

    public function update(Request $request, $id)
    {
        $paket = PaketWisata::findOrFail($id);

        $validated = $request->validate([
            'nama_paket' => 'required|string|max:150',
            'deskripsi' => 'required|string',
            'harga' => 'required|numeric|min:0',
            'rute' => 'required|string',
            'fasilitas' => 'required|string',
            'latitude' => 'nullable|string',
            'longitude' => 'nullable|string',
        ]);

        $paket->update($validated);

        return redirect()->route('admin.paket.index')->with('success', 'Paket Wisata berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $paket = PaketWisata::findOrFail($id);
        $paket->delete();

        return redirect()->route('admin.paket.index')->with('success', 'Paket Wisata berhasil dihapus!');
    }
}
