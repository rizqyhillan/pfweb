<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pet;
use App\Models\PetBreed;
use App\Models\PetType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PetController extends Controller
{
    public function index()
    {
        $pets = Pet::with('owner')->latest()->pathPaginate(15, url('admin/pets/page'));

        return view('admin.pets.index', compact('pets'));
    }

    public function create()
    {
        $owners = User::where('role', 'customer')->get();
        $types = PetType::orderBy('name')->get();
        $breeds = PetBreed::orderBy('name')->get();

        return view('admin.pets.create', compact('owners', 'types', 'breeds'));
    }

    public function store(Request $request)
    {
        $v = $request->validate([
            'id_pemilik' => 'required|exists:users,id',
            'nama_hewan' => 'required|string|max:100',
            'jenis' => 'required|string|max:50',
            'ras' => 'nullable|string|max:100',
            'umur' => 'nullable|string|max:30',
            'berat' => 'nullable|numeric|min:0',
            'catatan' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if (! empty($v['jenis'])) {
            PetType::firstOrCreate(['name' => $v['jenis']]);
        }
        if (! empty($v['ras'])) {
            PetBreed::firstOrCreate(['name' => $v['ras']]);
        }

        if ($request->hasFile('foto')) {
            $v['foto'] = $request->file('foto')->store('pets', 'public');
        }

        Pet::create($v);

        return redirect()->route('admin.pets.index')->with('success', 'Hewan berhasil ditambahkan.');
    }

    public function edit(Pet $pet)
    {
        $owners = User::where('role', 'customer')->get();
        $types = PetType::orderBy('name')->get();
        $breeds = PetBreed::orderBy('name')->get();

        return view('admin.pets.edit', compact('pet', 'owners', 'types', 'breeds'));
    }

    public function update(Request $request, Pet $pet)
    {
        $v = $request->validate([
            'id_pemilik' => 'required|exists:users,id',
            'nama_hewan' => 'required|string|max:100',
            'jenis' => 'required|string|max:50',
            'ras' => 'nullable|string|max:100',
            'umur' => 'nullable|string|max:30',
            'berat' => 'nullable|numeric|min:0',
            'catatan' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if (! empty($v['jenis'])) {
            PetType::firstOrCreate(['name' => $v['jenis']]);
        }
        if (! empty($v['ras'])) {
            PetBreed::firstOrCreate(['name' => $v['ras']]);
        }

        if ($request->hasFile('foto')) {
            if ($pet->foto && Storage::disk('public')->exists($pet->foto)) {
                Storage::disk('public')->delete($pet->foto);
            }
            $v['foto'] = $request->file('foto')->store('pets', 'public');
        }

        $pet->update($v);

        return redirect()->route('admin.pets.index')->with('success', 'Data hewan berhasil diperbarui.');
    }

    public function show(Pet $pet)
    {
        $pet->load(['owner', 'rekamMedis.dokter']);

        return view('admin.pets.show', compact('pet'));
    }

    public function destroy(Pet $pet)
    {
        if ($pet->foto && Storage::disk('public')->exists($pet->foto)) {
            Storage::disk('public')->delete($pet->foto);
        }
        $pet->delete();

        return redirect()->route('admin.pets.index')->with('success', 'Data hewan berhasil dihapus.');
    }
}
