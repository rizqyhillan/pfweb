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
    public function index(Request $request)
    {
        $query = Pet::with('owner');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->whereHas('owner', function ($qOwner) use ($search) {
                    $qOwner->where('nama', 'like', '%' . $search . '%');
                })->orWhere('nama_hewan', 'like', '%' . $search . '%');
            });
        }

        $pets = $query->latest()
            ->pathPaginate(15, url('admin/pets/page'))
            ->withQueryString();

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
        $validated = $request->validate([
            'id_pemilik'    => 'required|exists:users,id',
            'nama_hewan'    => 'required|string|max:100',
            'jenis'         => 'required|string|max:50',
            'jenis_kelamin' => 'nullable|string|max:20',
            'tanggal_lahir' => 'nullable|date',
            'ras'           => 'nullable|string|max:100',
            'berat'         => 'nullable|numeric',
            'catatan'       => 'nullable|string',
            'foto'          => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('pets', 'public');
        }

        if (!empty($validated['jenis'])) {
            PetType::firstOrCreate(['name' => $validated['jenis']]);
        }
        if (!empty($validated['ras'])) {
            PetBreed::firstOrCreate(['name' => $validated['ras']]);
        }

        Pet::create($validated);

        return redirect()->route('admin.pets.index')->with('success', 'Hewan berhasil ditambahkan');
    }


    public function edit(Pet $pet)
    {
        $owners = User::where('role', 'customer')->get();
        $types = PetType::orderBy('name')->get();
        $breeds = PetBreed::orderBy('name')->get();

        return view('admin.pets.edit', compact('pet', 'owners', 'types', 'breeds'));
    }

    public function update(Request $request, $id)
    {
        $pet = Pet::findOrFail($id);

        $validated = $request->validate([
            'id_pemilik'    => 'required|exists:users,id',
            'nama_hewan'    => 'required|string|max:100',
            'jenis'         => 'required|string|max:50',
            'jenis_kelamin' => 'nullable|string|max:20',
            'tanggal_lahir' => 'nullable|date',
            'ras'           => 'nullable|string|max:100',
            'berat'         => 'nullable|numeric',
            'catatan'       => 'nullable|string',
            'foto'          => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            if ($pet->foto && Storage::disk('public')->exists($pet->foto)) {
                Storage::disk('public')->delete($pet->foto);
            }
            $validated['foto'] = $request->file('foto')->store('pets', 'public');
        }

        if (!empty($validated['jenis'])) {
            PetType::firstOrCreate(['name' => $validated['jenis']]);
        }
        if (!empty($validated['ras'])) {
            PetBreed::firstOrCreate(['name' => $validated['ras']]);
        }

        $pet->update($validated);

        return redirect()->route('admin.pets.index')->with('success', 'Hewan berhasil diperbarui');
    }
    public function show(Pet $pet)
    {
        $pet->load(['owner', 'rekamMedis.dokter']);

        return view('admin.pets.show', compact('pet'));
    }

    public function destroy(Request $request, $id)
    {
        $pet = Pet::findOrFail($id);
    
        if ($pet->foto && Storage::disk('public')->exists($pet->foto)) {
            Storage::disk('public')->delete($pet->foto);
        }
    
        $pet->delete();
    
        return redirect()->route('admin.pets.index')->with('success', 'Hewan berhasil dihapus');
    }
}
