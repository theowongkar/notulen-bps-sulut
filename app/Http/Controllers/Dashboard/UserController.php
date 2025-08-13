<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // Cek akses
        if (Gate::denies('manage-user')) {
            abort(403, 'Anda tidak memiliki akses.');
        }

        // Validasi Search Form
        $validated = $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'search' => 'nullable|string|max:50',
        ]);

        // Ambil Nilai
        $start_date = $validated['start_date'] ?? null;
        $end_date = $validated['end_date'] ?? null;
        $search = $validated['search'] ?? null;

        // Ambil Semua Pengguna
        $users = User::when($search, function ($query, $search) {
            return $query->where(function ($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%");
            });
        })
            ->when($start_date, function ($query) use ($start_date) {
                return $query->whereDate('created_at', '>=', $start_date);
            })
            ->when($end_date, function ($query) use ($end_date) {
                return $query->whereDate('created_at', '<=', $end_date);
            })
            ->orderBy('created_at', 'DESC')
            ->paginate(10);


        return view('dashboard.users.index', compact('users'));
    }

    public function create()
    {
        // Cek akses
        if (Gate::denies('manage-user')) {
            abort(403, 'Anda tidak memiliki akses.');
        }

        return view('dashboard.users.create');
    }

    public function store(Request $request)
    {
        // Cek akses
        if (Gate::denies('manage-user')) {
            abort(403, 'Anda tidak memiliki akses.');
        }

        // Validasi Input
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:Admin,Notulis',
            'password' => 'required|string|min:8|confirmed',
            'avatar' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        // Simpan Gambar Avatar
        if ($request->hasFile('avatar')) {
            $validated['avatar'] = $request->file('avatar')->store('users', 'public');
        }

        // Simpan User
        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
            'avatar' => $validated['avatar'],
        ]);

        return redirect()->back()->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit(string $id)
    {
        // Cek akses
        if (Gate::denies('manage-user')) {
            abort(403, 'Anda tidak memiliki akses.');
        }

        // Ambil data pengguna berdasarkan id
        $user = User::findOrFail($id);

        return view('dashboard.users.edit', compact('user'));
    }

    public function update(Request $request, string $id)
    {
        // Cek akses
        if (Gate::denies('manage-user')) {
            abort(403, 'Anda tidak memiliki akses.');
        }

        // Ambil data pengguna berdasarkan id
        $user = User::findOrFail($id);

        // Validasi Input
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:Admin,Notulis',
            'password' => 'nullable|string|min:8|confirmed',
            'avatar' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        // Cek jika ada avatar baru
        if ($request->hasFile('avatar')) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            $validated['avatar'] = $request->file('avatar')->store('users', 'public');
        } else {
            $validated['avatar'] = $user->avatar;
        }

        // Update user
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'password' => $validated['password']
                ? Hash::make($validated['password'])
                : $user->password,
            'avatar' => $validated['avatar'],
        ]);

        return redirect()->back()->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        // Cek akses
        if (Gate::denies('manage-user')) {
            abort(403, 'Anda tidak memiliki akses.');
        }

        // Ambil data pengguna berdasarkan id
        $user = User::findOrFail($id);

        // Hapus avatar jika ada
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        // Hapus user
        $user->delete();

        return redirect()->route('dashboard.user.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}
