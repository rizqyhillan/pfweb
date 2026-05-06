<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pet;
use App\Models\User;
use Illuminate\Http\Request;

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

        return view('admin.pets.create', compact('owners'));
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
        ]);
        Pet::create($v);

        return redirect()->route('admin.pets.index')->with('success', 'Hewan berhasil ditambahkan.');
    }

    public function edit(Pet $pet)
    {
        $owners = User::where('role', 'customer')->get();

        return view('admin.pets.edit', compact('pet', 'owners'));
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
        ]);
        $pet->update($v);

        return redirect()->route('admin.pets.index')->with('success', 'Data hewan berhasil diperbarui.');
    }

    public function destroy(Pet $pet)
    {
        $pet->delete();

        return redirect()->route('admin.pets.index')->with('success', 'Data hewan berhasil dihapus.');
    }
}
