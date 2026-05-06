<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::with('dokter')->latest()->pathPaginate(15, url('admin/services/page'));

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

        return redirect()->route('admin.services.index')->with('success', 'Layanan berhasil ditambahkan.');
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

        return redirect()->route('admin.services.index')->with('success', 'Layanan berhasil diperbarui.');
    }

    public function destroy(Service $service)
    {
        $service->delete();

        return redirect()->route('admin.services.index')->with('success', 'Layanan berhasil dihapus.');
    }
}
