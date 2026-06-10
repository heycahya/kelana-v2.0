<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use App\Models\Ulasan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UlasanController extends Controller
{
    /**
     * Simpan ulasan customer untuk jadwal trip tertentu.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        // A. Validasi Request
        $validator = Validator::make($request->all(), [
            'id_jadwal' => 'required|integer|exists:jadwal_trip,id_jadwal',
            'rating' => 'required|integer|min:1|max:5',
            'komentar' => 'nullable|string',
        ], [
            'id_jadwal.required' => 'ID jadwal trip wajib diisi.',
            'id_jadwal.integer' => 'ID jadwal trip harus berupa angka.',
            'id_jadwal.exists' => 'Jadwal trip tidak ditemukan.',
            'rating.required' => 'Rating wajib diisi.',
            'rating.integer' => 'Rating harus berupa angka.',
            'rating.min' => 'Rating minimal bernilai 1.',
            'rating.max' => 'Rating maksimal bernilai 5.',
            'komentar.string' => 'Komentar harus berupa teks.',
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

        $id_customer = auth()->id();
        $id_jadwal = $request->id_jadwal;

        // B. Pengecekan Otorisasi (Hak Akses Tiket Lunas / status_pembayaran = 'PAID')
        $hasPaidBooking = Pemesanan::where('id_customer', $id_customer)
            ->where('id_jadwal', $id_jadwal)
            ->where('status_pembayaran', 'PAID')
            ->exists();

        if (!$hasPaidBooking) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden: Anda tidak memiliki riwayat pemesanan yang telah lunas untuk jadwal trip ini.'
            ], 403);
        }

        // C. Pengecekan Duplikasi Ulasan (Application Level)
        $hasReviewed = Ulasan::where('id_customer', $id_customer)
            ->where('id_jadwal', $id_jadwal)
            ->exists();

        if ($hasReviewed) {
            return response()->json([
                'success' => false,
                'message' => 'Conflict: Anda sudah memberikan ulasan untuk jadwal trip ini.'
            ], 409);
        }

        // D. Proses Simpan Data
        try {
            $ulasan = Ulasan::create([
                'id_customer' => $id_customer,
                'id_jadwal' => $id_jadwal,
                'rating' => $request->rating,
                'komentar' => $request->komentar,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Ulasan berhasil disimpan.',
                'data' => $ulasan
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan ulasan.'
            ], 500);
        }
    }
}
