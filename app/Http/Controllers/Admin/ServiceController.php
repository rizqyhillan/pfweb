<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Service::with('dokter');

        if ($jenis = $request->query('jenis_layanan')) {
            $query->where('jenis_layanan', $jenis);
        }

        $services = $query->latest()->pathPaginate(
            15,
            url('admin/services/page').($request->query('jenis_layanan') ? '?jenis_layanan='.urlencode($request->query('jenis_layanan')) : '')
        );

        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        $doctors = User::where('role', 'dokter')->get();

        return view('admin.services.create', compact('doctors'));
    }

    public function store(Request $request)
    {
        $v = $request->validate([
            'nama_layanan' => 'required|string|max:150',
            'jenis_layanan' => 'required|in:konsultasi,vaksinasi,grooming,operasi,penitipan,lainnya',
            'harga' => 'required|numeric|min:0',
            'durasi_menit' => 'nullable|integer|min:0',
            'deskripsi' => 'nullable|string',
            'id_dokter' => 'nullable|exists:users,id',
        ]);
        $v['is_aktif'] = $request->has('is_aktif') ? 1 : 1;
        Service::create($v);

        return redirect()->route('admin.services.index', $request->only('jenis_layanan'))->with('success', 'Layanan berhasil ditambahkan.');
    }

    public function edit(Service $service)
    {
        $doctors = User::where('role', 'dokter')->get();

        return view('admin.services.edit', compact('service', 'doctors'));
    }

    public function update(Request $request, Service $service)
    {
        $v = $request->validate([
            'nama_layanan' => 'required|string|max:150',
            'jenis_layanan' => 'required|in:konsultasi,vaksinasi,grooming,operasi,penitipan,lainnya',
            'harga' => 'required|numeric|min:0',
            'durasi_menit' => 'nullable|integer|min:0',
            'deskripsi' => 'nullable|string',
            'id_dokter' => 'nullable|exists:users,id',
        ]);
        $v['is_aktif'] = $request->has('is_aktif') ? 1 : 0;
        $service->update($v);

        return redirect()->route('admin.services.index', $request->only('jenis_layanan'))->with('success', 'Layanan berhasil diperbarui.');
    }

    public function destroy(Request $request, Service $service)
    {
        $hasTransactions = \App\Models\TransactionService::where('id_layanan', $service->id)->exists();
        $hasBookings = \App\Models\DoctorBooking::where('id_layanan', $service->id)->exists();

        if ($hasTransactions || $hasBookings) {
            return redirect()
                ->route('admin.services.index', $request->only('jenis_layanan'))
                ->with('error', 'Layanan tidak dapat dihapus karena telah digunakan dalam transaksi atau booking. Silakan nonaktifkan layanan ini jika tidak ingin digunakan lagi.');
        }

        $service->delete();

        return redirect()->route('admin.services.index', $request->only('jenis_layanan'))->with('success', 'Layanan berhasil dihapus.');
    }
}
