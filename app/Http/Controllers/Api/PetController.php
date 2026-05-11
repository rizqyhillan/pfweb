<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pet;
use Illuminate\Http\Request;

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
            'foto' => 'nullable|string',
            ]);

        $validated['id_pemilik'] = $request->user()->id;

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
            'foto' => 'nullable|string',
            ]);

    $pet->update($validated);

    return response()->json([
        'message' => 'Hewan berhasil diperbarui',
        'data' => $pet,
    ]);
}

public function destroy(Request $request, $id)
{
    $pet = Pet::where('id', $id)
        ->where('id_pemilik', $request->user()->id)
        ->firstOrFail();

    $pet->delete();

    return response()->json([
        'message' => 'Hewan berhasil dihapus',
    ]);
}
}