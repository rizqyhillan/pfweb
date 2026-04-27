<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Boarding;
use App\Models\Pet;
use App\Models\Room;
use Illuminate\Http\Request;

class BoardingController extends Controller
{
    public function index()
    {
        $boardings = Boarding::with(['pet.owner', 'room'])->latest()->paginate(15);
        return view('admin.boardings.index', compact('boardings'));
    }

    public function create()
    {
        $pets = Pet::with('owner')->get();
        $rooms = Room::where('status', 'available')->get();
        return view('admin.boardings.create', compact('pets', 'rooms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pet_id' => 'required|exists:pets,id',
            'room_id' => 'required|exists:rooms,id',
            'check_in_date' => 'required|date',
            'planned_check_out_date' => 'required|date|after:check_in_date',
            'drop_off_notes' => 'nullable|string',
            'total_cost' => 'required|numeric|min:0',
        ]);

        $validated['status'] = 'active';
        Boarding::create($validated);

        // Update room status
        Room::where('id', $validated['room_id'])->update(['status' => 'occupied']);

        return redirect()->route('admin.boardings.index')->with('success', 'Boarding berhasil dibuat.');
    }

    public function edit(Boarding $boarding)
    {
        $pets = Pet::with('owner')->get();
        $rooms = Room::get();
        return view('admin.boardings.edit', compact('boarding', 'pets', 'rooms'));
    }

    public function update(Request $request, Boarding $boarding)
    {
        $validated = $request->validate([
            'pet_id' => 'required|exists:pets,id',
            'room_id' => 'required|exists:rooms,id',
            'check_in_date' => 'required|date',
            'planned_check_out_date' => 'required|date|after:check_in_date',
            'check_out_date' => 'nullable|date',
            'drop_off_notes' => 'nullable|string',
            'pick_up_notes' => 'nullable|string',
            'status' => 'required|in:active,completed,cancelled',
            'total_cost' => 'required|numeric|min:0',
        ]);

        $boarding->update($validated);

        // If completed or cancelled, free the room
        if (in_array($validated['status'], ['completed', 'cancelled'])) {
            Room::where('id', $validated['room_id'])->update(['status' => 'available']);
        }

        return redirect()->route('admin.boardings.index')->with('success', 'Boarding berhasil diupdate.');
    }

    public function destroy(Boarding $boarding)
    {
        // Free the room
        Room::where('id', $boarding->room_id)->update(['status' => 'available']);
        $boarding->delete();
        return redirect()->route('admin.boardings.index')->with('success', 'Boarding berhasil dihapus.');
    }
}
