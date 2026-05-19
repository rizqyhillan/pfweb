<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Grooming;
use App\Models\PackageType;
use App\Models\Pet;
use Illuminate\Http\Request;

class GroomingController extends Controller
{
    public function index(Request $request)
    {
        $query = Grooming::with(['hewan.owner', 'paket']);

        if ($request->filled('paket')) {
            $query->where('id_paket', $request->paket);
        }

        $groomings = $query->latest()->pathPaginate(15, url('admin/groomings/page'));
        $packageOptions = PackageType::orderBy('label')->pluck('label', 'id')->toArray();
        $selectedPaket = $request->paket ?? '';

        return view('admin.groomings.index', compact('groomings', 'packageOptions', 'selectedPaket'));
    }

    public function create()
    {
        $pets = Pet::with('owner')->get();
        $packages = PackageType::where('name', '!=', 'basic')->orWhere('name', '!=', 'regular')->orWhere('name', '!=', 'premium')->get();
        // For grooming, use all package types
        $packages = PackageType::all();

        return view('admin.groomings.create', compact('pets', 'packages'));
    }

    public function store(Request $request)
    {
        $v = $request->validate([
            'id_hewan' => 'required|exists:hewan,id',
            'id_paket' => 'required|exists:package_types,id',
            'tanggal_grooming' => 'required|date',
            'waktu_grooming' => 'required|date_format:H:i',
            'catatan_grooming' => 'nullable|string',
            'total_biaya' => 'nullable|numeric|min:0',
        ]);

        $paket = PackageType::find($v['id_paket']);
        if (empty($v['total_biaya']) || $v['total_biaya'] == 0) {
            $v['total_biaya'] = $paket->harga_per_malam ?? 0;
        }
        $v['status'] = 'pending';
        $grooming = Grooming::create($v);

        return redirect()->route('admin.groomings.index')->with('success', 'Grooming berhasil dibuat. Biaya: Rp '.number_format($v['total_biaya'], 0, ',', '.'));
    }

    public function edit(Grooming $grooming)
    {
        $pets = Pet::with('owner')->get();
        $packages = PackageType::all();

        return view('admin.groomings.edit', compact('grooming', 'pets', 'packages'));
    }

    public function update(Request $request, Grooming $grooming)
    {
        $v = $request->validate([
            'id_hewan' => 'required|exists:hewan,id',
            'id_paket' => 'required|exists:package_types,id',
            'tanggal_grooming' => 'required|date',
            'waktu_grooming' => 'required|date_format:H:i',
            'catatan_grooming' => 'nullable|string',
            'status' => 'required|in:pending,aktif,selesai,batal',
            'total_biaya' => 'nullable|numeric|min:0',
        ]);

        if (empty($v['total_biaya']) || $v['total_biaya'] == 0) {
            $paket = PackageType::find($v['id_paket']);
            $v['total_biaya'] = $paket->harga_per_malam ?? 0;
        }
        $grooming->update($v);

        return redirect()->route('admin.groomings.index')->with('success', 'Grooming berhasil diperbarui.');
    }


    public function updateStatus(Request $request, Grooming $grooming)
    {
        $v = $request->validate([
            'status' => 'required|in:pending,aktif,selesai,batal',
        ]);

        $grooming->update($v);

        return redirect()->route('admin.groomings.index')->with('success', 'Status grooming berhasil diperbarui.');
    }

    public function destroy(Grooming $grooming)
    {
        $grooming->delete();

        return redirect()->route('admin.groomings.index')->with('success', 'Grooming berhasil dihapus.');
    }
}
