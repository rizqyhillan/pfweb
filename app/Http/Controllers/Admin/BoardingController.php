<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Boarding;
use App\Models\Pet;
use App\Models\Room;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BoardingController extends Controller
{
    public function index()
    {
        $boardings = Boarding::with(['hewan.owner', 'kamar'])->latest()->paginate(15);
        return view('admin.boardings.index', compact('boardings'));
    }

    public function create()
    {
        $pets = Pet::with('owner')->get();
        $rooms = Room::where('status', 'tersedia')->get();
        return view('admin.boardings.create', compact('pets', 'rooms'));
    }

    public function store(Request $request)
    {
        $v = $request->validate([
            'id_hewan' => 'required|exists:hewan,id',
            'id_kamar' => 'required|exists:kamar,id',
            'tanggal_masuk' => 'required|date',
            'tanggal_rencana_keluar' => 'required|date|after:tanggal_masuk',
            'catatan_titip' => 'nullable|string',
            'total_biaya' => 'nullable|numeric|min:0',
        ]);

        $room = Room::find($v['id_kamar']);
        $days = max(1, Carbon::parse($v['tanggal_masuk'])->diffInDays(Carbon::parse($v['tanggal_rencana_keluar'])));
        if (empty($v['total_biaya']) || $v['total_biaya'] == 0) {
            $v['total_biaya'] = $room->harga_per_hari * $days;
        }
        $v['status'] = 'aktif';
        Boarding::create($v);
        Room::where('id', $v['id_kamar'])->update(['status' => 'terisi']);
        return redirect()->route('admin.boardings.index')->with('success', 'Penitipan berhasil dibuat. Biaya: Rp ' . number_format($v['total_biaya'], 0, ',', '.'));
    }

    public function edit(Boarding $boarding)
    {
        $pets = Pet::with('owner')->get();
        $rooms = Room::get();
        return view('admin.boardings.edit', compact('boarding', 'pets', 'rooms'));
    }

    public function update(Request $request, Boarding $boarding)
    {
        $v = $request->validate([
            'id_hewan' => 'required|exists:hewan,id',
            'id_kamar' => 'required|exists:kamar,id',
            'tanggal_masuk' => 'required|date',
            'tanggal_rencana_keluar' => 'required|date|after:tanggal_masuk',
            'tanggal_keluar' => 'nullable|date',
            'catatan_titip' => 'nullable|string',
            'catatan_jemput' => 'nullable|string',
            'status' => 'required|in:aktif,selesai,batal',
            'total_biaya' => 'nullable|numeric|min:0',
        ]);

        if (empty($v['total_biaya']) || $v['total_biaya'] == 0) {
            $room = Room::find($v['id_kamar']);
            $endDate = $v['tanggal_keluar'] ? Carbon::parse($v['tanggal_keluar']) : Carbon::parse($v['tanggal_rencana_keluar']);
            $days = max(1, Carbon::parse($v['tanggal_masuk'])->diffInDays($endDate));
            $v['total_biaya'] = $room->harga_per_hari * $days;
        }
        $boarding->update($v);
        if (in_array($v['status'], ['selesai', 'batal'])) {
            Room::where('id', $v['id_kamar'])->update(['status' => 'tersedia']);
        }
        return redirect()->route('admin.boardings.index')->with('success', 'Penitipan berhasil diperbarui.');
    }

    public function destroy(Boarding $boarding)
    {
        Room::where('id', $boarding->id_kamar)->update(['status' => 'tersedia']);
        $boarding->delete();
        return redirect()->route('admin.boardings.index')->with('success', 'Penitipan berhasil dihapus.');
    }
}
