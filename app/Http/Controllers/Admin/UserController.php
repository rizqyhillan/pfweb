<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $role = $request->route('role') ?? $request->query('role');
        $query = User::latest();

        if ($role && in_array($role, ['admin', 'dokter', 'karyawan'])) {
            $query->where('role', $role);
            $users = $query->pathPaginate(15, url("admin/users/role/{$role}/page"));
        } else {
            $users = $query->pathPaginate(15, url('admin/users/page'));
        }

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
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:admin,dokter,karyawan',
            'no_hp' => 'nullable|string|regex:/^[0-9]+$/|max:20',
            'alamat' => 'nullable|string',
        ], [
            'no_hp.regex' => 'No. HP hanya boleh berisi angka.',
        ]);
        $v['password'] = bcrypt($v['password']);
        $v['is_aktif'] = 1;
        User::create($v);

        $role = $request->query('role');
        if ($role && in_array($role, ['admin', 'dokter', 'karyawan'])) {
            return redirect()->route('admin.users.role', ['role' => $role])->with('success', 'Pengguna berhasil ditambahkan.');
        }

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $rules = [
            'nama' => 'required|string|max:100',
            'email' => 'required|email|max:150|unique:users,email,'.$user->id,
            'role' => 'required|in:admin,dokter,karyawan',
            'no_hp' => 'nullable|string|regex:/^[0-9]+$/|max:20',
            'alamat' => 'nullable|string',
        ];
        if ($request->filled('password')) {
            $rules['password'] = 'required|string|min:6|confirmed';
        }
        $v = $request->validate($rules, [
            'no_hp.regex' => 'No. HP hanya boleh berisi angka.',
        ]);
        if ($request->filled('password')) {
            $v['password'] = bcrypt($request->password);
        }
        $v['is_aktif'] = $request->has('is_aktif') ? 1 : 0;
        $user->update($v);

        $role = $request->query('role');
        if ($role && in_array($role, ['admin', 'dokter', 'karyawan'])) {
            return redirect()->route('admin.users.role', ['role' => $role])->with('success', 'Pengguna berhasil diperbarui.');
        }

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function destroy(Request $request, User $user)
    {
        $user->delete();

        $role = $request->query('role');
        if ($role && in_array($role, ['admin', 'dokter', 'karyawan'])) {
            return redirect()->route('admin.users.role', ['role' => $role])->with('success', 'Pengguna berhasil dihapus.');
        }

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}
