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

        return view('admin.rooms.index', compact('rooms'));
    }

    public function create()
    {
        return view('admin.rooms.create');
    }

    public function store(Request $request)
    {
        $v = $request->validate([
            'nama_kamar' => 'required|string|max:50',
            'tipe' => 'required|in:kecil,sedang,besar',
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
            'tipe' => 'required|in:kecil,sedang,besar',
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
        $room->delete();

        return redirect()->route('admin.rooms.index')->with('success', 'Kamar berhasil dihapus.');
    }
}
