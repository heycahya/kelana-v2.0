<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaketWisata;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaketManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $paket = PaketWisata::orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'message' => 'Data paket wisata berhasil diambil',
            'data' => $paket
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_paket' => 'required|string|max:150',
            'deskripsi' => 'required|string',
            'harga' => 'required|numeric|min:0',
            'rute' => 'required|string',
            'fasilitas' => 'required|string',
        ], [
            'nama_paket.required' => 'Kolom nama paket wajib diisi.',
            'nama_paket.max' => 'Nama paket maksimal 150 karakter.',
            'deskripsi.required' => 'Kolom deskripsi wajib diisi.',
            'harga.required' => 'Kolom harga wajib diisi.',
            'harga.numeric' => 'Kolom harga harus berupa angka.',
            'harga.min' => 'Harga tidak boleh kurang dari 0.',
            'rute.required' => 'Kolom rute wajib diisi.',
            'fasilitas.required' => 'Kolom fasilitas wajib diisi.',
        ]);

        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->toArray() as $field => $messages) {
                $errors[$field] = $messages[0];
            }

            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $errors
            ], 422);
        }

        $paket = PaketWisata::create([
            'nama_paket' => $request->nama_paket,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
            'rute' => $request->rute,
            'fasilitas' => $request->fasilitas,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data paket wisata berhasil ditambahkan',
            'data' => $paket
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $paket = PaketWisata::find($id);

        if (!$paket) {
            return response()->json([
                'success' => false,
                'message' => 'Data paket wisata tidak ditemukan',
                'data' => null
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail paket wisata',
            'data' => $paket
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $paket = PaketWisata::find($id);

        if (!$paket) {
            return response()->json([
                'success' => false,
                'message' => 'Data paket wisata tidak ditemukan',
                'data' => null
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nama_paket' => 'required|string|max:150',
            'deskripsi' => 'required|string',
            'harga' => 'required|numeric|min:0',
            'rute' => 'required|string',
            'fasilitas' => 'required|string',
        ], [
            'nama_paket.required' => 'Kolom nama paket wajib diisi.',
            'nama_paket.max' => 'Nama paket maksimal 150 karakter.',
            'deskripsi.required' => 'Kolom deskripsi wajib diisi.',
            'harga.required' => 'Kolom harga wajib diisi.',
            'harga.numeric' => 'Kolom harga harus berupa angka.',
            'harga.min' => 'Harga tidak boleh kurang dari 0.',
            'rute.required' => 'Kolom rute wajib diisi.',
            'fasilitas.required' => 'Kolom fasilitas wajib diisi.',
        ]);

        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->toArray() as $field => $messages) {
                $errors[$field] = $messages[0];
            }

            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $errors
            ], 422);
        }

        $paket->update([
            'nama_paket' => $request->nama_paket,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
            'rute' => $request->rute,
            'fasilitas' => $request->fasilitas,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data paket wisata berhasil diperbarui',
            'data' => $paket
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $paket = PaketWisata::find($id);

        if (!$paket) {
            return response()->json([
                'success' => false,
                'message' => 'Data paket wisata tidak ditemukan',
                'data' => null
            ], 404);
        }

        $paket->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data paket wisata berhasil dihapus',
            'data' => null
        ], 200);
    }
}
