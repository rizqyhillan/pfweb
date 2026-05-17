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
        $validated = $request->validate([
            'nama_hewan' => 'required|string|max:100',
            'jenis' => 'required|string|max:50',
            'jenis_kelamin' => 'nullable|string|max:20',
            'tanggal_lahir' => 'nullable|date',
            'ras' => 'nullable|string|max:100',
            'umur' => 'nullable|string|max:30',
            'berat' => 'nullable|numeric',
            'catatan' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $validated['id_pemilik'] = $request->user()->id;

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('pets', 'public');
        }

        $pet = Pet::create($validated);

        return response()->json([
            'message' => 'Hewan berhasil ditambahkan',
            'data' => $pet,
        ], 201);
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
        $pet = Pet::where('id', $id)
            ->where('id_pemilik', $request->user()->id)
            ->firstOrFail();

        $validated = $request->validate([
            'nama_hewan' => 'required|string|max:100',
            'jenis' => 'required|string|max:50',
            'jenis_kelamin' => 'nullable|string|max:20',
            'tanggal_lahir' => 'nullable|date',
            'ras' => 'nullable|string|max:100',
            'umur' => 'nullable|string|max:30',
            'berat' => 'nullable|numeric',
            'catatan' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            if ($pet->foto && Storage::disk('public')->exists($pet->foto)) {
                Storage::disk('public')->delete($pet->foto);
            }

            $validated['foto'] = $request->file('foto')->store('pets', 'public');
        }

        $pet->update($validated);

        return response()->json([
            'message' => 'Hewan berhasil diperbarui',
            'data' => $pet,
        ]);
    }

    public function show(Pet $pet)
    {
        $pet->load(['owner', 'rekamMedis.dokter']);

        return view('admin.pets.show', compact('pet'));
    }

    public function destroy(Request $request, $id)
    {
        $pet = Pet::where('id', $id)
            ->where('id_pemilik', $request->user()->id)
            ->firstOrFail();
    
        if ($pet->foto && Storage::disk('public')->exists($pet->foto)) {
            Storage::disk('public')->delete($pet->foto);
        }
    
        $pet->delete();
    
        return response()->json([
            'message' => 'Hewan berhasil dihapus',
        ]);
    }
}
