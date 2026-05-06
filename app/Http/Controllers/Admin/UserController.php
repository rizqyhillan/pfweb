<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->pathPaginate(15, url('admin/users/page'));

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $v = $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|max:150|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,dokter,karyawan',
            'no_hp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
        ]);
        $v['password'] = bcrypt($v['password']);
        $v['is_aktif'] = 1;
        User::create($v);

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $v = $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|max:150|unique:users,email,'.$user->id,
            'role' => 'required|in:admin,dokter,karyawan',
            'no_hp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
        ]);
        if ($request->filled('password')) {
            $v['password'] = bcrypt($request->password);
        }
        $v['is_aktif'] = $request->has('is_aktif') ? 1 : 0;
        $user->update($v);

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}
