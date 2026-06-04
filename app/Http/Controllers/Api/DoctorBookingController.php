<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DoctorBooking;
use App\Models\DoctorSchedule;
use App\Models\Pet;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DoctorBookingController extends Controller
{
    public function doctors()
    {
        $activeDoctorIds = Service::where('kategori', 'dokter')
            ->whereNotNull('id_dokter')
            ->where('is_aktif', true)
            ->pluck('id_dokter');

        $doctors = User::where('role', 'dokter')
            ->where('is_aktif', true)
            ->whereIn('id', $activeDoctorIds)
            ->select([
                'id',
                'nama',
                'email',
                'no_hp',
                'alamat',
                'foto',
            ])
            ->orderBy('nama')
            ->get()
            ->map(function ($doctor) {
                return [
                    'id' => $doctor->id,
                    'nama' => $doctor->nama,
                    'email' => $doctor->email,
                    'no_hp' => $doctor->no_hp,
                    'alamat' => $doctor->alamat,
                    'foto' => $doctor->foto,
                    'foto_url' => $doctor->foto_url,
                    'spesialis' => 'Dokter Hewan',
                    'pengalaman' => 'Berpengalaman',
                    'rating' => 4.9,
                    'tersedia' => true,
                ];
            });

        return response()->json([
            'data' => $doctors,
        ]);
    }

    public function services(Request $request)
    {
        $query = Service::where('kategori', 'dokter')
            ->where('is_aktif', true)
            ->orderBy('nama_layanan');

        if ($request->filled('doctor_id')) {
            $query->where(function ($q) use ($request) {
                $q->whereNull('id_dokter')
                    ->orWhere('id_dokter', $request->doctor_id);
            });
        }

        $services = $query->get()->map(function ($service) {
            return [
                'id' => $service->id,
                'nama_layanan' => $service->nama_layanan,
                'kategori' => $service->kategori,
                'deskripsi' => $service->deskripsi,
                'harga' => (float) $service->harga,
                'estimasi_biaya' => (float) $service->harga,
                'id_dokter' => $service->id_dokter,
                'is_aktif' => (bool) $service->is_aktif,
            ];
        });

        return response()->json([
            'data' => $services,
        ]);
    }

    public function schedules(Request $request)
    {
        $query = DoctorSchedule::with('dokter')
            ->where('is_aktif', true)
            ->orderByRaw("CASE hari WHEN 'senin' THEN 1 WHEN 'selasa' THEN 2 WHEN 'rabu' THEN 3 WHEN 'kamis' THEN 4 WHEN 'jumat' THEN 5 WHEN 'sabtu' THEN 6 WHEN 'minggu' THEN 7 END")
            ->orderBy('jam_mulai');

        if ($request->filled('doctor_id')) {
            $query->where('id_dokter', $request->doctor_id);
        }

        $schedules = $query->get()->map(function ($schedule) {
            return [
                'id' => $schedule->id,
                'id_dokter' => $schedule->id_dokter,
                'nama_dokter' => $schedule->dokter->nama ?? '-',
                'hari' => $schedule->hari,
                'jam_mulai' => $schedule->jam_mulai,
                'jam_selesai' => $schedule->jam_selesai,
                'is_aktif' => (bool) $schedule->is_aktif,
            ];
        });

        return response()->json([
            'data' => $schedules,
        ]);
    }

    public function availability(Request $request)
    {
        $validated = $request->validate([
            'doctor_id' => ['required', 'exists:users,id'],
            'days' => ['nullable', 'integer', 'min:5', 'max:14'],
        ]);

        $doctor = User::where('id', $validated['doctor_id'])
            ->where('role', 'dokter')
            ->where('is_aktif', true)
            ->firstOrFail();

        $daysCount = $validated['days'] ?? 6;
        $hariMap = [
            'Monday' => 'senin',
            'Tuesday' => 'selasa',
            'Wednesday' => 'rabu',
            'Thursday' => 'kamis',
            'Friday' => 'jumat',
            'Saturday' => 'sabtu',
            'Sunday' => 'minggu',
        ];

        $schedules = DoctorSchedule::where('id_dokter', $doctor->id)
            ->where('is_aktif', true)
            ->orderByRaw("CASE hari WHEN 'senin' THEN 1 WHEN 'selasa' THEN 2 WHEN 'rabu' THEN 3 WHEN 'kamis' THEN 4 WHEN 'jumat' THEN 5 WHEN 'sabtu' THEN 6 WHEN 'minggu' THEN 7 END")
            ->orderBy('jam_mulai')
            ->get()
            ->groupBy('hari');

        $days = [];
        $now = Carbon::now();

        for ($i = 1; $i <= $daysCount; $i++) {
            $date = $now->copy()->addDays($i);
            $hari = $hariMap[$date->format('l')] ?? null;
            $dateSchedules = $hari && $schedules->has($hari)
                ? $schedules->get($hari)
                : collect();

            $pagi = [];
            $siang = [];

            foreach ($dateSchedules as $schedule) {
                $start = Carbon::parse($schedule->jam_mulai);
                $end = Carbon::parse($schedule->jam_selesai);

                while ($start->lt($end)) {
                    $slot = $start->format('H:i');
                    $isBooked = DoctorBooking::where('id_dokter', $doctor->id)
                        ->where('id_jadwal', $schedule->id)
                        ->whereDate('tanggal_booking', $date->format('Y-m-d'))
                        ->where('jam_booking', $slot)
                        ->whereNotIn('status', ['batal'])
                        ->exists();

                    if (!$isBooked) {
                        $item = [
                            'id_jadwal' => $schedule->id,
                            'time' => $slot,
                            'jam_mulai' => Carbon::parse($schedule->jam_mulai)->format('H:i'),
                            'jam_selesai' => Carbon::parse($schedule->jam_selesai)->format('H:i'),
                            'sisa_kuota' => 1,
                        ];

                        if ((int) $start->format('H') < 12) {
                            $pagi[] = $item;
                        } else {
                            $siang[] = $item;
                        }
                    }

                    $start->addHour();
                }
            }

            $days[] = [
                'day' => $date->locale('id')->isoFormat('ddd'),
                'date' => $date->format('d'),
                'full_date' => $date->format('Y-m-d'),
                'month_year' => $date->locale('id')->isoFormat('MMMM YYYY'),
                'hari' => $hari,
                'available' => count($pagi) > 0 || count($siang) > 0,
                'times' => [
                    'pagi' => $pagi,
                    'siang' => $siang,
                ],
            ];
        }

        return response()->json([
            'data' => [
                'doctor' => [
                    'id' => $doctor->id,
                    'nama' => $doctor->nama,
                ],
                'days' => $days,
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_hewan' => ['required', 'exists:hewan,id'],
            'id_dokter' => ['required', 'exists:users,id'],
            'id_layanan' => ['required', 'exists:layanan,id'],
            'id_jadwal' => ['nullable', 'exists:jadwal_dokter,id'],
            'tanggal_booking' => ['required', 'date', 'after_or_equal:today'],
            'jam_booking' => ['required', 'date_format:H:i'],
            'keluhan' => ['nullable', 'string'],
        ]);

        $pet = Pet::where('id', $validated['id_hewan'])
            ->where('id_pemilik', $request->user()->id)
            ->firstOrFail();

        $doctor = User::where('id', $validated['id_dokter'])
            ->where('role', 'dokter')
            ->where('is_aktif', true)
            ->firstOrFail();

        $service = Service::where('id', $validated['id_layanan'])
            ->where('kategori', 'dokter')
            ->where('is_aktif', true)
            ->where(function ($query) use ($doctor) {
                $query->whereNull('id_dokter')
                    ->orWhere('id_dokter', $doctor->id);
            })
            ->firstOrFail();

        $this->validateScheduleSlot(
            doctorId: $doctor->id,
            scheduleId: $validated['id_jadwal'] ?? null,
            tanggalBooking: $validated['tanggal_booking'],
            jamBooking: $validated['jam_booking']
        );

        $booking = DoctorBooking::create([
            'id_hewan' => $pet->id,
            'id_dokter' => $doctor->id,
            'id_layanan' => $service->id,
            'id_jadwal' => $validated['id_jadwal'] ?? null,
            'tanggal_booking' => $validated['tanggal_booking'],
            'jam_booking' => $validated['jam_booking'],
            'keluhan' => $validated['keluhan'] ?? null,
            'catatan_dokter' => null,
            'status' => 'pending',
            'total_biaya' => $service->harga,
        ]);

        $booking->load([
            'hewan',
            'dokter',
            'layanan',
            'jadwal',
        ]);

        return response()->json([
            'message' => 'Booking dokter berhasil dibuat. Pembayaran dilakukan di lokasi.',
            'data' => $this->formatBooking($booking),
        ], 201);
    }

    public function myBookings(Request $request)
    {
        $bookings = DoctorBooking::with([
                'hewan',
                'dokter',
                'layanan',
                'jadwal',
            ])
            ->whereHas('hewan', function ($query) use ($request) {
                $query->where('id_pemilik', $request->user()->id);
            })
            ->latest()
            ->get()
            ->map(fn ($booking) => $this->formatBooking($booking));

        return response()->json([
            'data' => $bookings,
        ]);
    }

    public function show(Request $request, $id)
    {
        $booking = DoctorBooking::with([
                'hewan',
                'dokter',
                'layanan',
                'jadwal',
            ])
            ->where('id', $id)
            ->whereHas('hewan', function ($query) use ($request) {
                $query->where('id_pemilik', $request->user()->id);
            })
            ->firstOrFail();

        return response()->json([
            'data' => $this->formatBooking($booking),
        ]);
    }



    public function reschedule(Request $request, $id)
    {
        $validated = $request->validate([
            'tanggal_booking' => ['required', 'date', 'after_or_equal:today'],
            'jam_booking' => ['required', 'date_format:H:i'],
            'id_jadwal' => ['nullable', 'exists:jadwal_dokter,id'],
        ]);

        $booking = DoctorBooking::with([
                'hewan',
                'dokter',
                'layanan',
                'jadwal',
            ])
            ->where('id', $id)
            ->whereHas('hewan', function ($query) use ($request) {
                $query->where('id_pemilik', $request->user()->id);
            })
            ->firstOrFail();

        if (!in_array($booking->status, ['pending'])) {
            return response()->json([
                'message' => 'Booking dokter hanya bisa diubah jadwal jika status masih pending.',
            ], 422);
        }

        $this->validateScheduleSlot(
            doctorId: $booking->id_dokter,
            scheduleId: $validated['id_jadwal'] ?? null,
            tanggalBooking: $validated['tanggal_booking'],
            jamBooking: $validated['jam_booking'],
            ignoreBookingId: $booking->id
        );

        $booking->update([
            'id_jadwal' => $validated['id_jadwal'] ?? $booking->id_jadwal,
            'tanggal_booking' => $validated['tanggal_booking'],
            'jam_booking' => $validated['jam_booking'],
        ]);

        return response()->json([
            'message' => 'Jadwal booking dokter berhasil diubah.',
            'data' => $this->formatBooking($booking->fresh(['hewan', 'dokter', 'layanan', 'jadwal'])),
        ]);
    }

    public function cancel(Request $request, $id)
    {
        $booking = DoctorBooking::with([
                'hewan',
                'dokter',
                'layanan',
                'jadwal',
            ])
            ->where('id', $id)
            ->whereHas('hewan', function ($query) use ($request) {
                $query->where('id_pemilik', $request->user()->id);
            })
            ->firstOrFail();

        if (!in_array($booking->status, ['pending'])) {
            return response()->json([
                'message' => 'Booking dokter hanya bisa dibatalkan jika status masih pending.',
            ], 422);
        }

        $booking->update([
            'status' => 'batal',
        ]);

        return response()->json([
            'message' => 'Booking dokter berhasil dibatalkan.',
            'data' => $this->formatBooking($booking->fresh(['hewan', 'dokter', 'layanan', 'jadwal'])),
        ]);
    }

    private function validateScheduleSlot(int $doctorId, ?int $scheduleId, string $tanggalBooking, string $jamBooking, ?int $ignoreBookingId = null): void
    {
        if (empty($scheduleId)) {
            return;
        }

        $schedule = DoctorSchedule::where('id', $scheduleId)
            ->where('id_dokter', $doctorId)
            ->where('is_aktif', true)
            ->firstOrFail();

        $hariMap = [
            'Monday' => 'senin',
            'Tuesday' => 'selasa',
            'Wednesday' => 'rabu',
            'Thursday' => 'kamis',
            'Friday' => 'jumat',
            'Saturday' => 'sabtu',
            'Sunday' => 'minggu',
        ];

        $tanggal = Carbon::parse($tanggalBooking);
        $hariBooking = $hariMap[$tanggal->format('l')] ?? null;

        if ($hariBooking !== $schedule->hari) {
            throw new \Illuminate\Http\Exceptions\HttpResponseException(response()->json([
                'message' => 'Tanggal booking tidak sesuai dengan hari jadwal dokter.',
            ], 422));
        }

        $jam = Carbon::createFromFormat('H:i', $jamBooking);
        $jamMulai = Carbon::parse($schedule->jam_mulai);
        $jamSelesai = Carbon::parse($schedule->jam_selesai);

        if ($jam->lt($jamMulai) || $jam->gte($jamSelesai)) {
            throw new \Illuminate\Http\Exceptions\HttpResponseException(response()->json([
                'message' => 'Jam booking berada di luar jadwal dokter.',
            ], 422));
        }

        $query = DoctorBooking::where('id_dokter', $doctorId)
            ->where('id_jadwal', $schedule->id)
            ->whereDate('tanggal_booking', $tanggalBooking)
            ->where('jam_booking', $jamBooking)
            ->whereNotIn('status', ['batal']);

        if ($ignoreBookingId !== null) {
            $query->where('id', '!=', $ignoreBookingId);
        }

        if ($query->exists()) {
            throw new \Illuminate\Http\Exceptions\HttpResponseException(response()->json([
                'message' => 'Jam booking tidak tersedia. Dokter sudah memiliki booking pada tanggal dan jam tersebut.',
            ], 422));
        }
    }

    private function formatBooking(DoctorBooking $booking): array
    {
        return [
            'id' => $booking->id,
            'id_hewan' => $booking->id_hewan,
            'nama_hewan' => $booking->hewan->nama_hewan ?? '-',
            'jenis_hewan' => $booking->hewan->jenis ?? '-',

            'id_dokter' => $booking->id_dokter,
            'nama_dokter' => $booking->dokter->nama ?? '-',

            'id_layanan' => $booking->id_layanan,
            'nama_layanan' => $booking->layanan->nama_layanan ?? '-',
            'deskripsi_layanan' => $booking->layanan->deskripsi ?? null,

            'id_jadwal' => $booking->id_jadwal,
            'hari_jadwal' => $booking->jadwal->hari ?? null,
            'jam_mulai_jadwal' => $booking->jadwal->jam_mulai ?? null,
            'jam_selesai_jadwal' => $booking->jadwal->jam_selesai ?? null,

            'tanggal_booking' => optional($booking->tanggal_booking)->format('Y-m-d'),
            'jam_booking' => $booking->jam_booking ? Carbon::parse($booking->jam_booking)->format('H:i') : null,
            'keluhan' => $booking->keluhan,
            'catatan_dokter' => $booking->catatan_dokter,
            'status' => $booking->status,

            'estimasi_biaya' => (float) $booking->total_biaya,
            'total_biaya' => (float) $booking->total_biaya,
            'metode_pembayaran' => 'Bayar di lokasi',
            'payment_note' => 'Pembayaran dilakukan di lokasi setelah layanan selesai.',
        ];
    }

    public function checkBookingAvailability(Request $request)
    {
        $validated = $request->validate([
            'id_dokter' => ['required', 'exists:users,id'],
            'tanggal_booking' => ['required', 'date'],
            'jam_booking' => ['required', 'date_format:H:i'],
        ]);

        $existingBooking = DoctorBooking::where('id_dokter', $validated['id_dokter'])
            ->where('tanggal_booking', $validated['tanggal_booking'])
            ->where('jam_booking', $validated['jam_booking'])
            ->whereNotIn('status', ['batal'])
            ->exists();

        return response()->json([
            'available' => !$existingBooking,
            'message' => $existingBooking 
                ? 'Jam booking tidak tersedia untuk dokter ini pada tanggal dan jam tersebut.'
                : 'Jam booking tersedia.',
        ]);
    }
}