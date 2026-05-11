<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        return response()->json([
            'data' => $request->user()
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'no_hp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
        ]);

        $user->update($validated);

        return response()->json([
            'message' => 'Profil berhasil diperbarui',
            'data' => $user
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