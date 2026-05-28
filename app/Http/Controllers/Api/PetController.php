<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PetController extends Controller
{
    public function index(Request $request)
    {
        $pets = Pet::where('id_pemilik', $request->user()->id)
            ->latest()
            ->get();

        return response()->json($pets);
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
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $validated['id_pemilik'] = $request->user()->id;

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('pets', 'public');
        }

        if (!empty($validated['jenis'])) {
            \App\Models\PetType::firstOrCreate(['name' => $validated['jenis']]);
        }

        if (!empty($validated['ras'])) {
            \App\Models\PetBreed::firstOrCreate(['name' => $validated['ras']]);
        }

        $pet = Pet::create($validated);

        return response()->json([
            'message' => 'Hewan berhasil ditambahkan',
            'data' => $pet,
        ], 201);
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
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            if (!empty($pet->foto) && Storage::disk('public')->exists($pet->foto)) {
                Storage::disk('public')->delete($pet->foto);
            }

            $validated['foto'] = $request->file('foto')->store('pets', 'public');
        }

        if (!empty($validated['jenis'])) {
            \App\Models\PetType::firstOrCreate(['name' => $validated['jenis']]);
        }

        if (!empty($validated['ras'])) {
            \App\Models\PetBreed::firstOrCreate(['name' => $validated['ras']]);
        }

        $pet->update($validated);

        return response()->json([
            'message' => 'Hewan berhasil diperbarui',
            'data' => $pet->fresh(),
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $pet = Pet::where('id', $id)
            ->where('id_pemilik', $request->user()->id)
            ->firstOrFail();

        if (!empty($pet->foto) && Storage::disk('public')->exists($pet->foto)) {
            Storage::disk('public')->delete($pet->foto);
        }

        $pet->delete();

        return response()->json([
            'message' => 'Hewan berhasil dihapus',
        ]);
    }
}
