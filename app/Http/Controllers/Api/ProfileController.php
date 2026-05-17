<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'data' => [
                'id' => $user->id,
                'nama' => $user->nama ?? $user->name ?? '',
                'name' => $user->name ?? $user->nama ?? '',
                'email' => $user->email,
                'no_hp' => $user->no_hp ?? null,
                'alamat' => $user->alamat ?? null,
                'foto' => $user->foto ? asset('storage/' . $user->foto) : null,
            ],
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();
    
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'no_hp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
    
        if ($request->hasFile('foto')) {
            if ($user->foto && Storage::disk('public')->exists($user->foto)) {
                Storage::disk('public')->delete($user->foto);
            }
    
            $validated['foto'] = $request->file('foto')->store('users', 'public');
        }
    
        if (isset($validated['nama'])) {
            if (array_key_exists('nama', $user->getAttributes())) {
                $user->nama = $validated['nama'];
            } else {
                $user->name = $validated['nama'];
            }
    
            unset($validated['nama']);
        }
    
        foreach ($validated as $key => $value) {
            if ($key === 'email' || array_key_exists($key, $user->getAttributes())) {
                $user->{$key} = $value;
            }
        }
    
        $user->save();
    
        return response()->json([
            'message' => 'Profil berhasil diperbarui',
            'data' => [
                'id' => $user->id,
                'nama' => $user->nama ?? $user->name ?? '',
                'name' => $user->name ?? $user->nama ?? '',
                'email' => $user->email,
                'no_hp' => $user->no_hp ?? null,
                'alamat' => $user->alamat ?? null,
                'foto' => $user->foto ? asset('storage/' . $user->foto) : null,
            ],
        ]);
    }

    public function changePassword(Request $request)
{
    $validated = $request->validate([
        'current_password' => 'required',
        'new_password' => 'required|min:6|confirmed',
    ]);

    $user = $request->user();

    if (!Hash::check($validated['current_password'], $user->password)) {
        return response()->json([
            'message' => 'Password lama tidak sesuai'
        ], 400);
    }

    $user->password = Hash::make($validated['new_password']);
    $user->save();

    return response()->json([
        'message' => 'Password berhasil diperbarui'
    ]);
}
}