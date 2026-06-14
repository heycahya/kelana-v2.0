<?php

namespace App\Http\Controllers\CustomerWeb;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use App\Models\Ulasan;
use Illuminate\Http\Request;

class UlasanWebController extends Controller
{
    /**
     * Store a customer review for a trip schedule.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_jadwal' => 'required|integer|exists:jadwal_trip,id_jadwal',
            'rating' => 'required|integer|min:1|max:5',
            'komentar' => 'nullable|string',
        ]);

        $id_customer = auth()->id();
        $id_jadwal = $request->id_jadwal;

        // Check if customer has a PAID booking for this schedule
        $hasPaidBooking = Pemesanan::where('id_customer', $id_customer)
            ->where('id_jadwal', $id_jadwal)
            ->where('status_pembayaran', 'PAID')
            ->exists();

        if (!$hasPaidBooking) {
            return back()->with('error', 'Anda tidak memiliki riwayat pemesanan yang telah lunas untuk jadwal trip ini.');
        }

        // Check for duplicate review
        $hasReviewed = Ulasan::where('id_customer', $id_customer)
            ->where('id_jadwal', $id_jadwal)
            ->exists();

        if ($hasReviewed) {
            return back()->with('error', 'Anda sudah memberikan ulasan untuk jadwal trip ini.');
        }

        Ulasan::create([
            'id_customer' => $id_customer,
            'id_jadwal' => $id_jadwal,
            'rating' => $request->rating,
            'komentar' => $request->komentar,
        ]);

        return back()->with('success', 'Terima kasih atas ulasan Anda!');
    }
}
