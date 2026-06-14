<?php

namespace App\Http\Controllers\Api\Publik;

use App\Http\Controllers\Controller;
use App\Models\PaketWisata;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KatalogController extends Controller
{
    /**
     * Display a listing of active tour packages with filtering and search.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        // Validate incoming search and filter parameters
        $validator = Validator::make($request->all(), [
            'search' => 'nullable|string|max:100',
            'location' => 'nullable|string|max:100',
            'start_date' => 'nullable|date_format:Y-m-d',
            'end_date' => 'nullable|date_format:Y-m-d|after_or_equal:start_date',
            'per_page' => 'nullable|integer|min:1|max:100',
        ], [
            'search.string' => 'Parameter search harus berupa teks.',
            'search.max' => 'Parameter search maksimal 100 karakter.',
            'location.string' => 'Parameter lokasi harus berupa teks.',
            'location.max' => 'Parameter lokasi maksimal 100 karakter.',
            'start_date.date_format' => 'Format start_date harus Y-m-d.',
            'end_date.date_format' => 'Format end_date harus Y-m-d.',
            'end_date.after_or_equal' => 'Tanggal selesai harus setelah atau sama dengan tanggal mulai.',
            'per_page.integer' => 'Parameter per_page harus berupa angka.',
            'per_page.min' => 'Parameter per_page minimal 1.',
            'per_page.max' => 'Parameter per_page maksimal 100.',
        ]);

        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->toArray() as $field => $messages) {
                $errors[$field] = $messages[0];
            }

            return response()->json([
                'success' => false,
                'message' => 'Validasi filter gagal',
                'errors' => $errors
            ], 422);
        }

        $query = PaketWisata::query()
            ->withCount('reviews')
            ->withAvg('reviews', 'rating');

        // Search by package name
        $query->when($request->filled('search'), function ($q) use ($request) {
            $q->where('nama_paket', 'like', '%' . $request->search . '%');
        });

        // Filter by location (rute)
        $query->when($request->filled('location'), function ($q) use ($request) {
            $q->where('rute', 'like', '%' . $request->location . '%');
        });

        // Filter by trip departure date range
        $query->when($request->filled('start_date') && $request->filled('end_date'), function ($q) use ($request) {
            $q->whereHas('jadwalTrip', function ($qSchedule) use ($request) {
                $qSchedule->whereBetween('tanggal_mulai', [$request->start_date, $request->end_date])
                          ->where('status_trip', 'Open');
            });
        });

        // Always eager load the active schedules
        $query->with(['jadwalTrip' => function ($q) {
            $q->where('status_trip', 'Open');
        }]);

        // Standard Laravel pagination
        $packages = $query->paginate($request->get('per_page', 10));

        // Format the average rating to 1 decimal place
        $packages->getCollection()->transform(function ($item) {
            if (!is_null($item->reviews_avg_rating)) {
                $item->reviews_avg_rating = round((float)$item->reviews_avg_rating, 1);
            }
            return $item;
        });

        return response()->json([
            'success' => true,
            'message' => 'Katalog paket wisata berhasil diambil',
            'data' => $packages
        ], 200);
    }

    /**
     * Display details of a specific tour package with active schedules and recent reviews.
     *
     * @param  mixed  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        // Enforce numeric ID to prevent path traversal or other injection attempts if it is not integer
        if (!is_numeric($id)) {
            return response()->json([
                'success' => false,
                'message' => 'ID paket tidak valid',
                'data' => null
            ], 400);
        }

        $paket = PaketWisata::withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->with([
                'jadwalTrip' => function ($q) {
                    $q->where('status_trip', 'Open')
                      ->where('tanggal_mulai', '>=', now()->toDateString())
                      ->orderBy('tanggal_mulai', 'asc');
                },
                'reviews' => function ($q) {
                    $q->latest()->limit(5)->with('customer:id_customer,nama_customer');
                }
            ])
            ->find($id);

        if (!$paket) {
            return response()->json([
                'success' => false,
                'message' => 'Data paket wisata tidak ditemukan',
                'data' => null
            ], 404);
        }

        if (!is_null($paket->reviews_avg_rating)) {
            $paket->reviews_avg_rating = round((float)$paket->reviews_avg_rating, 1);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail paket wisata',
            'data' => $paket
        ], 200);
    }
}
