<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Boarding;
use App\Models\Pet;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BoardingController extends Controller
{
    public function rooms()
    {
        $rooms = Room::query()
            ->where('status', 'tersedia')
            ->orderBy('paket')
            ->orderBy('harga_per_hari')
            ->get()
            ->map(fn ($room) => $this->formatRoom($room));

        return response()->json([
            'data' => $rooms,
        ]);
    }

    public function roomDetail($id)
    {
        $room = Room::where('status', 'tersedia')->findOrFail($id);

        return response()->json([
            'data' => $this->formatRoom($room),
        ]);
    }

    public function estimate(Request $request)
    {
        $validated = $request->validate([
            'id_kamar' => ['required', 'exists:kamar,id'],
            'tanggal_masuk' => ['required', 'date', 'after_or_equal:today'],
            'tanggal_rencana_keluar' => ['required', 'date', 'after:tanggal_masuk'],
        ]);

        $room = Room::findOrFail($validated['id_kamar']);

        $tanggalMasuk = Carbon::parse($validated['tanggal_masuk'])->startOfDay();
        $tanggalKeluar = Carbon::parse($validated['tanggal_rencana_keluar'])->startOfDay();

        $jumlahHari = max(1, $tanggalMasuk->diffInDays($tanggalKeluar));
        $totalBiaya = $jumlahHari * $room->harga_per_hari;

        return response()->json([
            'data' => [
                'id_kamar' => $room->id,
                'nama_kamar' => $room->nama_kamar,
                'paket' => $room->paket,
                'harga_per_hari' => (float) $room->harga_per_hari,
                'tanggal_masuk' => $tanggalMasuk->format('Y-m-d'),
                'tanggal_rencana_keluar' => $tanggalKeluar->format('Y-m-d'),
                'jumlah_hari' => $jumlahHari,
                'estimasi_biaya' => (float) $totalBiaya,
                'metode_pembayaran' => 'Bayar di lokasi',
                'payment_note' => 'Pembayaran dilakukan di lokasi saat check-in atau setelah penitipan selesai.',
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_hewan' => ['required', 'exists:hewan,id'],
            'id_kamar' => ['required', 'exists:kamar,id'],
            'tanggal_masuk' => ['required', 'date', 'after_or_equal:today'],
            'tanggal_rencana_keluar' => ['required', 'date', 'after:tanggal_masuk'],
            'catatan_titip' => ['nullable', 'string'],
            'catatan' => ['nullable', 'string'],
        ]);

        $pet = Pet::where('id', $validated['id_hewan'])
            ->where('id_pemilik', $request->user()->id)
            ->firstOrFail();

        $room = Room::where('id', $validated['id_kamar'])
            ->where('status', 'tersedia')
            ->firstOrFail();

        if ($room->terisi >= $room->kapasitas) {
            return response()->json([
                'message' => 'Kamar sudah penuh.',
            ], 422);
        }

        $tanggalMasuk = Carbon::parse($validated['tanggal_masuk'])->startOfDay();
        $tanggalKeluar = Carbon::parse($validated['tanggal_rencana_keluar'])->startOfDay();

        $jumlahHari = max(1, $tanggalMasuk->diffInDays($tanggalKeluar));
        $totalBiaya = $jumlahHari * $room->harga_per_hari;

        $boarding = Boarding::create([
            'id_hewan' => $pet->id,
            'id_kamar' => $room->id,
            'tanggal_masuk' => $tanggalMasuk,
            'tanggal_rencana_keluar' => $tanggalKeluar,
            'tanggal_keluar' => null,
            'status' => 'pending',
            'total_biaya' => $totalBiaya,
            'catatan_titip' => $validated['catatan_titip'] ?? $validated['catatan'] ?? null,
        ]);

        $boarding->load([
            'hewan.owner',
            'kamar',
        ]);

        return response()->json([
            'message' => 'Booking penitipan berhasil dibuat. Pembayaran dilakukan di lokasi.',
            'data' => $this->formatBoarding($boarding),
        ], 201);
    }

    public function myBoardings(Request $request)
    {
        $boardings = Boarding::with([
                'hewan.owner',
                'kamar',
            ])
            ->whereHas('hewan', function ($query) use ($request) {
                $query->where('id_pemilik', $request->user()->id);
            })
            ->latest('tanggal_masuk')
            ->get()
            ->map(fn ($boarding) => $this->formatBoarding($boarding));

        return response()->json([
            'data' => $boardings,
        ]);
    }

    public function show(Request $request, $id)
    {
        $boarding = Boarding::with([
                'hewan.owner',
                'kamar',
            ])
            ->where('id', $id)
            ->whereHas('hewan', function ($query) use ($request) {
                $query->where('id_pemilik', $request->user()->id);
            })
            ->firstOrFail();

        return response()->json([
            'data' => $this->formatBoarding($boarding),
        ]);
    }


    public function reschedule(Request $request, $id)
    {
        $validated = $request->validate([
            'tanggal_masuk' => ['required', 'date', 'after_or_equal:today'],
            'tanggal_rencana_keluar' => ['required', 'date', 'after:tanggal_masuk'],
        ]);

        $boarding = Boarding::with([
                'hewan.owner',
                'kamar',
            ])
            ->where('id', $id)
            ->whereHas('hewan', function ($query) use ($request) {
                $query->where('id_pemilik', $request->user()->id);
            })
            ->firstOrFail();

        if (!in_array($boarding->status, ['pending'])) {
            return response()->json([
                'message' => 'Booking penitipan hanya bisa diubah jadwal jika status masih pending.',
            ], 422);
        }

        $tanggalMasuk = Carbon::parse($validated['tanggal_masuk'])->startOfDay();
        $tanggalKeluar = Carbon::parse($validated['tanggal_rencana_keluar'])->startOfDay();
        $jumlahHari = max(1, $tanggalMasuk->diffInDays($tanggalKeluar));
        $totalBiaya = $jumlahHari * ($boarding->kamar->harga_per_hari ?? 0);

        $boarding->update([
            'tanggal_masuk' => $tanggalMasuk,
            'tanggal_rencana_keluar' => $tanggalKeluar,
            'total_biaya' => $totalBiaya,
        ]);

        return response()->json([
            'message' => 'Jadwal booking penitipan berhasil diubah.',
            'data' => $this->formatBoarding($boarding->fresh(['hewan.owner', 'kamar'])),
        ]);
    }

    public function cancel(Request $request, $id)
    {
        $boarding = Boarding::with([
                'hewan.owner',
                'kamar',
            ])
            ->where('id', $id)
            ->whereHas('hewan', function ($query) use ($request) {
                $query->where('id_pemilik', $request->user()->id);
            })
            ->firstOrFail();

        if (!in_array($boarding->status, ['pending'])) {
            return response()->json([
                'message' => 'Penitipan hanya bisa dibatalkan jika status masih pending.',
            ], 422);
        }

        $boarding->update([
            'status' => 'batal',
        ]);

        return response()->json([
            'message' => 'Booking penitipan berhasil dibatalkan.',
            'data' => $this->formatBoarding($boarding->fresh(['hewan.owner', 'kamar'])),
        ]);
    }

    private function formatRoom(Room $room): array
    {
        return [
            'id' => $room->id,
            'nama_kamar' => $room->nama_kamar,
            'paket' => $room->paket,
            'kapasitas' => (int) $room->kapasitas,
            'terisi' => (int) $room->terisi,
            'sisa_kapasitas' => max(0, (int) $room->kapasitas - (int) $room->terisi),
            'harga_per_hari' => (float) $room->harga_per_hari,
            'fasilitas' => $room->fasilitas,
            'status' => $room->status,
            'tersedia' => $room->status === 'tersedia' && $room->terisi < $room->kapasitas,
        ];
    }

    private function formatBoarding(Boarding $boarding): array
    {
        $tanggalMasuk = $boarding->tanggal_masuk ? Carbon::parse($boarding->tanggal_masuk) : null;
        $tanggalKeluar = $boarding->tanggal_rencana_keluar ? Carbon::parse($boarding->tanggal_rencana_keluar) : null;

        $jumlahHari = ($tanggalMasuk && $tanggalKeluar)
            ? max(1, $tanggalMasuk->startOfDay()->diffInDays($tanggalKeluar->startOfDay()))
            : null;

        return [
            'id' => $boarding->id,

            'id_hewan' => $boarding->id_hewan,
            'nama_hewan' => $boarding->hewan->nama_hewan ?? '-',
            'jenis_hewan' => $boarding->hewan->jenis ?? null,
            'ras_hewan' => $boarding->hewan->ras ?? null,

            'id_kamar' => $boarding->id_kamar,
            'nama_kamar' => $boarding->kamar->nama_kamar ?? '-',
            'paket_kamar' => $boarding->kamar->paket ?? null,
            'fasilitas_kamar' => $boarding->kamar->fasilitas ?? null,

            'tanggal_masuk' => optional($boarding->tanggal_masuk)->format('Y-m-d'),
            'tanggal_rencana_keluar' => optional($boarding->tanggal_rencana_keluar)->format('Y-m-d'),
            'tanggal_keluar' => optional($boarding->tanggal_keluar)->format('Y-m-d'),

            'jumlah_hari' => $jumlahHari,
            'harga_per_hari' => $boarding->kamar ? (float) $boarding->kamar->harga_per_hari : null,
            'estimasi_biaya' => (float) $boarding->total_biaya,
            'total_biaya' => (float) $boarding->total_biaya,

            'status' => $boarding->status,
            'catatan' => $boarding->catatan_titip,
            'catatan_titip' => $boarding->catatan_titip,
            'catatan_jemput' => $boarding->catatan_jemput,

            'metode_pembayaran' => 'Bayar di lokasi',
            'payment_note' => 'Pembayaran dilakukan di lokasi saat check-in atau setelah penitipan selesai.',

            'created_at' => optional($boarding->created_at)->format('Y-m-d H:i:s'),
            'updated_at' => optional($boarding->updated_at)->format('Y-m-d H:i:s'),
        ];
    }
}