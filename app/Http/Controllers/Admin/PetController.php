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
        $pets = Pet::with('owner')->latest()->paginate(15);
        return view('admin.pets.index', compact('pets'));
    }

    public function create()
    {
        $owners = User::where('role', 'owner')->get();
        return view('admin.pets.create', compact('owners'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'owner_id' => 'required|exists:users,id',
            'name' => 'required|string|max:100',
            'species' => 'required|string|max:50',
            'breed' => 'nullable|string|max:100',
            'age' => 'nullable|string|max:30',
            'weight' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        Pet::create($validated);

        return redirect()->route('admin.pets.index')->with('success', 'Hewan berhasil ditambahkan.');
    }

    public function edit(Pet $pet)
    {
        $owners = User::where('role', 'owner')->get();
        return view('admin.pets.edit', compact('pet', 'owners'));
    }

    public function update(Request $request, Pet $pet)
    {
        $validated = $request->validate([
            'owner_id' => 'required|exists:users,id',
            'name' => 'required|string|max:100',
            'species' => 'required|string|max:50',
            'breed' => 'nullable|string|max:100',
            'age' => 'nullable|string|max:30',
            'weight' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $pet->update($validated);

        return redirect()->route('admin.pets.index')->with('success', 'Data hewan berhasil diupdate.');
    }

    public function destroy(Pet $pet)
    {
        $pet->delete();
        return redirect()->route('admin.pets.index')->with('success', 'Data hewan berhasil dihapus.');
    }
}
