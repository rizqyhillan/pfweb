<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PackageType;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        $query = Room::query();

        if ($request->filled('paket')) {
            $query->where('paket', $request->paket);
        }

        $rooms = $query->latest()->pathPaginate(15, url('admin/rooms/page'));

        // Hitung ringkasan per paket
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

        $paketOptions = Room::paketOptions();
        $selectedPaket = $request->paket ?? '';

        return view('admin.rooms.index', compact('rooms', 'paketSummary', 'paketOptions', 'selectedPaket'));
    }

    public function create()
    {
        $packageTypes = PackageType::options();

        $packagePrices = PackageType::prices();

        return view('admin.rooms.create', compact('packageTypes', 'packagePrices'));
    }

    public function store(Request $request)
    {
        $packageTypes = PackageType::options();
        $packageRule = ['required', Rule::in(array_keys($packageTypes))];

        $v = $request->validate([
            'nama_kamar' => 'required|string|max:50',
            'paket' => $packageRule,
            'harga_per_hari' => 'required|numeric|min:0',
            'kapasitas' => 'required|integer|min:1',
            'keterangan' => 'nullable|string',
            'foto_kamar' => ['nullable', 'array', 'max:8'],
            'foto_kamar.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $v['status'] = 'tersedia';
        $v['foto_urls'] = $this->storeRoomPhotos($request);
        Room::create($v);

        return redirect()->route('admin.rooms.index')->with('success', 'Kamar berhasil ditambahkan.');
    }

    public function edit(Room $room)
    {
        $packageTypes = PackageType::options();

        $packagePrices = PackageType::prices();

        return view('admin.rooms.edit', compact('room', 'packageTypes', 'packagePrices'));
    }

    public function update(Request $request, Room $room)
    {
        $packageTypes = PackageType::options();
        $packageRule = ['required', Rule::in(array_keys($packageTypes))];

        $v = $request->validate([
            'nama_kamar' => 'required|string|max:50',
            'paket' => $packageRule,
            'harga_per_hari' => 'required|numeric|min:0',
            'kapasitas' => 'required|integer|min:1',
            'status' => 'required|in:tersedia,terisi,maintenance',
            'keterangan' => 'nullable|string',
            'foto_kamar' => ['nullable', 'array', 'max:8'],
            'foto_kamar.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'hapus_foto' => ['nullable', 'array'],
            'hapus_foto.*' => ['string'],
        ]);

        $existingPhotos = collect($room->foto_urls ?? [])->values();
        $photosToDelete = collect($request->input('hapus_foto', []))->filter()->values();

        if ($photosToDelete->isNotEmpty()) {
            $existingPhotos = $existingPhotos->reject(fn ($path) => $photosToDelete->contains($path))->values();
            $photosToDelete->each(fn ($path) => Storage::disk('public')->delete($path));
        }

        $newPhotos = $this->storeRoomPhotos($request);
        $v['foto_urls'] = $existingPhotos
            ->merge($newPhotos)
            ->unique()
            ->values()
            ->all();

        $room->update($v);

        return redirect()->route('admin.rooms.index')->with('success', 'Kamar berhasil diperbarui.');
    }

    public function destroy(Room $room)
    {
        $activeBoardings = $room->boardings()->whereIn('status', ['pending', 'aktif'])->count();

        if ($activeBoardings > 0) {
            return redirect()->route('admin.rooms.index')
                ->with('error', "Kamar '{$room->nama_kamar}' tidak dapat dihapus karena masih digunakan oleh {$activeBoardings} boarding aktif/pending.");
        }

        collect($room->foto_urls ?? [])
            ->filter()
            ->each(fn ($path) => Storage::disk('public')->delete($path));

        $room->delete();

        return redirect()->route('admin.rooms.index')->with('success', 'Kamar berhasil dihapus.');
    }

    private function storeRoomPhotos(Request $request): array
    {
        if (! $request->hasFile('foto_kamar')) {
            return [];
        }

        return collect($request->file('foto_kamar'))
            ->filter()
            ->map(fn ($file) => $file->store('rooms', 'public'))
            ->values()
            ->all();
    }
}
