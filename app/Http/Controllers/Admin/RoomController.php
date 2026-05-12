<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::latest()->pathPaginate(15, url('admin/rooms/page'));

        // Hitung ringkasan per paket/tipe
        $paketSummary = Room::query()
            ->groupBy('paket')
            ->selectRaw('paket, COUNT(*) as total, MIN(harga_per_hari) as harga')
            ->selectRaw('SUM(CASE WHEN status = "tersedia" THEN 1 ELSE 0 END) as tersedia')
            ->selectRaw('SUM(CASE WHEN status = "terisi" THEN 1 ELSE 0 END) as terisi')
            ->selectRaw('SUM(CASE WHEN status = "maintenance" THEN 1 ELSE 0 END) as maintenance')
            ->get()
            ->keyBy('paket')
            ->mapWithKeys(function ($item) {
                return [
                    $item->paket => [
                        'total' => $item->total,
                        'harga' => $item->harga,
                        'tersedia' => $item->tersedia ?? 0,
                        'terisi' => $item->terisi ?? 0,
                        'maintenance' => $item->maintenance ?? 0,
                    ],
                ];
            });

        return view('admin.rooms.index', compact('rooms', 'paketSummary'));
    }

    public function create()
    {
        return view('admin.rooms.create');
    }

    public function store(Request $request)
    {
        $v = $request->validate([
            'nama_kamar' => 'required|string|max:50',
            'paket' => 'required|in:basic,regular,premium',
            'harga_per_hari' => 'required|numeric|min:0',
            'kapasitas' => 'required|integer|min:1',
            'keterangan' => 'nullable|string',
        ]);
        $v['status'] = 'tersedia';
        Room::create($v);

        return redirect()->route('admin.rooms.index')->with('success', 'Kamar berhasil ditambahkan.');
    }

    public function edit(Room $room)
    {
        return view('admin.rooms.edit', compact('room'));
    }

    public function update(Request $request, Room $room)
    {
        $v = $request->validate([
            'nama_kamar' => 'required|string|max:50',
            'paket' => 'required|in:basic,regular,premium',
            'harga_per_hari' => 'required|numeric|min:0',
            'kapasitas' => 'required|integer|min:1',
            'status' => 'required|in:tersedia,terisi,maintenance',
            'keterangan' => 'nullable|string',
        ]);
        $room->update($v);

        return redirect()->route('admin.rooms.index')->with('success', 'Kamar berhasil diperbarui.');
    }

    public function destroy(Room $room)
    {
        // Cek apakah kamar masih digunakan oleh boarding aktif atau pending
        $activeBoardings = $room->boardings()->whereIn('status', ['pending', 'aktif'])->count();

        if ($activeBoardings > 0) {
            return redirect()->route('admin.rooms.index')
                ->with('error', "Kamar '{$room->nama_kamar}' tidak dapat dihapus karena masih digunakan oleh {$activeBoardings} boarding aktif/pending.");
        }

        $room->delete();

        return redirect()->route('admin.rooms.index')->with('success', 'Kamar berhasil dihapus.');
    }
}
