<?php

namespace App\Http\Controllers\AdminWeb;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Pemesanan;
use Illuminate\Http\Request;

class CustomerWebController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::query();

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_customer', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('no_telp', 'like', "%{$search}%")
                  ->orWhere('alamat', 'like', "%{$search}%");
            });
        }

        $customers = $query->orderBy('id_customer', 'desc')->paginate(10);
        
        foreach ($customers as $c) {
            $c->bookings_count = Pemesanan::where('id_customer', $c->id_customer)->count();
            $c->total_spent = Pemesanan::where('id_customer', $c->id_customer)
                ->where('status_pembayaran', 'PAID')
                ->sum('total_harga');
        }
        
        return view('admin.customer.index', compact('customers'));
    }
}
