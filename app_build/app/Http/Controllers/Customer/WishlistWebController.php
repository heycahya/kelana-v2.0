<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use App\Models\PaketWisata;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistWebController extends Controller
{
    /**
     * Display a listing of the customer's wishlist.
     */
    public function index()
    {
        $customerId = Auth::guard('customer')->id();
        if (!$customerId) {
            return response()->json([], 401);
        }

        $wishlist = Wishlist::where('customer_id', $customerId)
            ->with('paketWisata.galleries')
            ->get()
            ->map(function ($item) {
                $paket = $item->paketWisata;
                if (!$paket) return null;

                $primaryImage = $paket->galleries->where('is_primary', true)->first()?->image_url 
                    ?? $paket->galleries->first()?->image_url 
                    ?? 'https://images.unsplash.com/photo-1506012787146-f92b2d7d6d96?auto=format&fit=crop&w=800&q=80';

                return [
                    'id' => $item->id,
                    'paket_wisata_id' => $paket->id_paket,
                    'nama' => $paket->nama_paket,
                    'harga' => $paket->harga,
                    'gambar' => $primaryImage,
                    'rute' => $paket->rute,
                    'url' => route('paket.detail', $paket->id_paket),
                ];
            })
            ->filter()
            ->values();

        return response()->json($wishlist);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $customerId = Auth::guard('customer')->id();
        if (!$customerId) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'paket_wisata_id' => 'required|exists:paket_wisata,id_paket',
        ]);

        Wishlist::firstOrCreate([
            'customer_id' => $customerId,
            'paket_wisata_id' => $request->paket_wisata_id,
        ]);

        return response()->json(['status' => 'success', 'message' => 'Added to wishlist']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $customerId = Auth::guard('customer')->id();
        if (!$customerId) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        Wishlist::where('customer_id', $customerId)
            ->where('paket_wisata_id', $id)
            ->delete();

        return response()->json(['status' => 'success', 'message' => 'Removed from wishlist']);
    }
}
