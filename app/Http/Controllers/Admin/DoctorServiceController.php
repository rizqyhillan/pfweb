<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;

class DoctorServiceController extends Controller
{
    public function index()
    {
        $services = Service::with('dokter')
            ->where('kategori', 'dokter')
            ->latest()
            ->get();

        return view('admin.doctor-services.index', compact('services'));
    }

    public function create()
    {
        $doctors = User::where('role', 'dokter')
            ->where('is_aktif', true)
            ->orderBy('nama')
            ->get();

        return view('admin.doctor-services.create', compact('doctors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_layanan' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'harga' => ['required', 'numeric', 'min:0'],
            'id_dokter' => ['nullable', 'exists:users,id'],
            'is_aktif' => ['nullable', 'boolean'],
        ]);

        $validated['kategori'] = 'dokter';
        $validated['is_aktif'] = $request->boolean('is_aktif');

        Service::create($validated);

        return redirect()
            ->route('admin.doctor-services.index')
            ->with('success', 'Layanan dokter berhasil ditambahkan.');
    }

    public function show(Service $doctorService)
    {
        abort_if($doctorService->kategori !== 'dokter', 404);

        $doctorService->load('dokter');

        return view('admin.doctor-services.show', compact('doctorService'));
    }

    public function edit(Service $doctorService)
    {
        abort_if($doctorService->kategori !== 'dokter', 404);

        $doctors = User::where('role', 'dokter')
            ->where('is_aktif', true)
            ->orderBy('nama')
            ->get();

        return view('admin.doctor-services.edit', compact('doctorService', 'doctors'));
    }

    public function update(Request $request, Service $doctorService)
    {
        abort_if($doctorService->kategori !== 'dokter', 404);

        $validated = $request->validate([
            'nama_layanan' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'harga' => ['required', 'numeric', 'min:0'],
            'id_dokter' => ['nullable', 'exists:users,id'],
            'is_aktif' => ['nullable', 'boolean'],
        ]);

        $validated['kategori'] = 'dokter';
        $validated['is_aktif'] = $request->boolean('is_aktif');

        $doctorService->update($validated);

        return redirect()
            ->route('admin.doctor-services.index')
            ->with('success', 'Layanan dokter berhasil diperbarui.');
    }

    public function destroy(Service $doctorService)
    {
        abort_if($doctorService->kategori !== 'dokter', 404);

        $hasTransactions = \App\Models\TransactionService::where('id_layanan', $doctorService->id)->exists();
        $hasBookings = \App\Models\DoctorBooking::where('id_layanan', $doctorService->id)->exists();

        if ($hasTransactions || $hasBookings) {
            return redirect()
                ->route('admin.doctor-services.index')
                ->with('error', 'Layanan tidak dapat dihapus karena telah digunakan dalam transaksi atau booking. Silakan nonaktifkan layanan ini jika tidak ingin digunakan lagi.');
        }

        $doctorService->delete();

        return redirect()
            ->route('admin.doctor-services.index')
            ->with('success', 'Layanan dokter berhasil dihapus.');
    }
}