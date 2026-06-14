<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\JadwalTrip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JadwalTripController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $jadwal = JadwalTrip::with(['paketWisata', 'tripLeader'])->orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'message' => 'Data jadwal trip berhasil diambil',
            'data' => $jadwal
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
            'id_paket' => 'required|integer|exists:paket_wisata,id_paket',
            'id_leader' => 'required|integer|exists:trip_leaders,id_leader',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'kuota' => 'required|integer|min:1',
            'status_trip' => 'required|string|in:Draft,Open,Berjalan,Selesai,Batal',
        ], [
            'id_paket.required' => 'Kolom ID paket wajib diisi.',
            'id_paket.integer' => 'Kolom ID paket harus berupa angka.',
            'id_paket.exists' => 'Paket wisata yang dipilih tidak valid atau tidak ditemukan.',
            'id_leader.required' => 'Kolom ID leader wajib diisi.',
            'id_leader.integer' => 'Kolom ID leader harus berupa angka.',
            'id_leader.exists' => 'Trip leader yang dipilih tidak valid atau tidak ditemukan.',
            'tanggal_mulai.required' => 'Kolom tanggal mulai wajib diisi.',
            'tanggal_mulai.date' => 'Kolom tanggal mulai harus berupa format tanggal.',
            'tanggal_selesai.required' => 'Kolom tanggal selesai wajib diisi.',
            'tanggal_selesai.date' => 'Kolom tanggal selesai harus berupa format tanggal.',
            'tanggal_selesai.after_or_equal' => 'Kolom tanggal selesai harus bernilai setelah atau sama dengan tanggal mulai.',
            'kuota.required' => 'Kolom kuota wajib diisi.',
            'kuota.integer' => 'Kolom kuota harus berupa angka.',
            'kuota.min' => 'Kuota tidak boleh kurang dari 1.',
            'status_trip.required' => 'Kolom status trip wajib diisi.',
            'status_trip.in' => 'Status trip tidak valid. Pilih dari: Draft, Open, Berjalan, Selesai, Batal.',
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

        $jadwal = JadwalTrip::create([
            'id_paket' => $request->id_paket,
            'id_leader' => $request->id_leader,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'kuota' => $request->kuota,
            'sisa_kuota' => $request->kuota,
            'status_trip' => $request->status_trip,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data jadwal trip berhasil ditambahkan',
            'data' => $jadwal
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
        $jadwal = JadwalTrip::with(['paketWisata', 'tripLeader'])->find($id);

        if (!$jadwal) {
            return response()->json([
                'success' => false,
                'message' => 'Data jadwal trip tidak ditemukan',
                'data' => null
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail jadwal trip',
            'data' => $jadwal
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
        $jadwal = JadwalTrip::find($id);

        if (!$jadwal) {
            return response()->json([
                'success' => false,
                'message' => 'Data jadwal trip tidak ditemukan',
                'data' => null
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'id_paket' => 'required|integer|exists:paket_wisata,id_paket',
            'id_leader' => 'required|integer|exists:trip_leaders,id_leader',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'kuota' => 'required|integer|min:1',
            'status_trip' => 'required|string|in:Draft,Open,Berjalan,Selesai,Batal',
        ], [
            'id_paket.required' => 'Kolom ID paket wajib diisi.',
            'id_paket.integer' => 'Kolom ID paket harus berupa angka.',
            'id_paket.exists' => 'Paket wisata yang dipilih tidak valid atau tidak ditemukan.',
            'id_leader.required' => 'Kolom ID leader wajib diisi.',
            'id_leader.integer' => 'Kolom ID leader harus berupa angka.',
            'id_leader.exists' => 'Trip leader yang dipilih tidak valid atau tidak ditemukan.',
            'tanggal_mulai.required' => 'Kolom tanggal mulai wajib diisi.',
            'tanggal_mulai.date' => 'Kolom tanggal mulai harus berupa format tanggal.',
            'tanggal_selesai.required' => 'Kolom tanggal selesai wajib diisi.',
            'tanggal_selesai.date' => 'Kolom tanggal selesai harus berupa format tanggal.',
            'tanggal_selesai.after_or_equal' => 'Kolom tanggal selesai harus bernilai setelah atau sama dengan tanggal mulai.',
            'kuota.required' => 'Kolom kuota wajib diisi.',
            'kuota.integer' => 'Kolom kuota harus berupa angka.',
            'kuota.min' => 'Kuota tidak boleh kurang dari 1.',
            'status_trip.required' => 'Kolom status trip wajib diisi.',
            'status_trip.in' => 'Status trip tidak valid. Pilih dari: Draft, Open, Berjalan, Selesai, Batal.',
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

        \Illuminate\Support\Facades\DB::transaction(function () use ($request, $jadwal) {
            $jadwal = JadwalTrip::lockForUpdate()->find($jadwal->id_jadwal);
            
            // Adjust sisa_kuota if kuota changes
            $diff = $request->kuota - $jadwal->kuota;
            
            $jadwal->update([
                'id_paket' => $request->id_paket,
                'id_leader' => $request->id_leader,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'kuota' => $request->kuota,
                'sisa_kuota' => max(0, $jadwal->sisa_kuota + $diff),
                'status_trip' => $request->status_trip,
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Data jadwal trip berhasil diperbarui',
            'data' => $jadwal
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
        $jadwal = JadwalTrip::find($id);

        if (!$jadwal) {
            return response()->json([
                'success' => false,
                'message' => 'Data jadwal trip tidak ditemukan',
                'data' => null
            ], 404);
        }

        $jadwal->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data jadwal trip berhasil dihapus',
            'data' => null
        ], 200);
    }
}
