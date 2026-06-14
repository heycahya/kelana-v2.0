<?php

namespace App\Http\Controllers\AdminWeb;

use App\Http\Controllers\Controller;
use App\Models\JadwalTrip;
use App\Models\PaketWisata;
use App\Models\TripLeader;
use Illuminate\Http\Request;

class JadwalWebController extends Controller
{
    public function index(Request $request)
    {
        $query = JadwalTrip::with(['paketWisata', 'tripLeader']);

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('paketWisata', function($p) use ($search) {
                    $p->where('nama_paket', 'like', "%{$search}%")
                      ->orWhere('rute', 'like', "%{$search}%");
                })
                ->orWhereHas('tripLeader', function($tl) use ($search) {
                    $tl->where('nama_leader', 'like', "%{$search}%");
                });
            });
        }

        $jadwals = $query->orderBy('tanggal_mulai', 'desc')->paginate(10);
        return view('admin.jadwal.index', compact('jadwals'));
    }

    public function create()
    {
        $pakets = PaketWisata::all();
        $leaders = TripLeader::all();
        return view('admin.jadwal.create', compact('pakets', 'leaders'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_paket' => 'required|integer|exists:paket_wisata,id_paket',
            'id_leader' => 'required|integer|exists:trip_leaders,id_leader',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'kuota' => 'required|integer|min:1',
            'status_trip' => 'required|string|in:Draft,Open,Berjalan,Selesai,Batal',
        ]);

        $validated['sisa_kuota'] = $validated['kuota'];

        JadwalTrip::create($validated);

        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal dan Penugasan Leader berhasil dibuat!');
    }

    public function show($id)
    {
        $jadwal = JadwalTrip::with(['paketWisata', 'tripLeader'])->findOrFail($id);
        return view('admin.jadwal.show', compact('jadwal'));
    }

    public function edit($id)
    {
        $jadwal = JadwalTrip::findOrFail($id);
        $pakets = PaketWisata::all();
        $leaders = TripLeader::all();
        return view('admin.jadwal.edit', compact('jadwal', 'pakets', 'leaders'));
    }

    public function update(Request $request, $id)
    {
        $jadwal = JadwalTrip::findOrFail($id);

        $validated = $request->validate([
            'id_paket' => 'required|integer|exists:paket_wisata,id_paket',
            'id_leader' => 'required|integer|exists:trip_leaders,id_leader',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'kuota' => 'required|integer|min:1',
            'status_trip' => 'required|string|in:Draft,Open,Berjalan,Selesai,Batal',
        ]);

        // Adjust sisa_kuota if kuota changes
        $diff = $validated['kuota'] - $jadwal->kuota;
        $validated['sisa_kuota'] = max(0, $jadwal->sisa_kuota + $diff);

        $jadwal->update($validated);

        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal dan Penugasan Leader berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $jadwal = JadwalTrip::findOrFail($id);
        $jadwal->delete();

        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal dan Penugasan Leader berhasil dihapus!');
    }
}
