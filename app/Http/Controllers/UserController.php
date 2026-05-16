<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    private function gate(): void
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) abort(403);
    }

    public function index(Request $request)
    {
        $this->gate();
        $query = User::query();
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('department', 'like', '%' . $request->search . '%');
            });
        }
        if ($request->role) $query->where('role', $request->role);
        $users = $query->latest()->paginate(10)->withQueryString();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $this->gate();
        return view('users.create');
    }

    public function store(Request $request)
    {
        $this->gate();
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|min:6|confirmed',
            'role'       => 'required|in:admin,karyawan',
            'phone'      => 'nullable|string|max:20',
            'department' => 'nullable|string|max:100',
            'is_active'  => 'boolean',
        ]);

        $validated['password']  = Hash::make($validated['password']);
        $validated['is_active'] = $request->boolean('is_active', true);

        $user = User::create($validated);
        ActivityLog::record('create_user', "User '{$user->name}' ditambahkan", $user);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        $this->gate();
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $this->gate();
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email,' . $user->id,
            'password'   => 'nullable|min:6|confirmed',
            'role'       => 'required|in:admin,karyawan',
            'phone'      => 'nullable|string|max:20',
            'department' => 'nullable|string|max:100',
            'is_active'  => 'boolean',
        ]);

        if (empty($validated['password'])) {
            unset($validated['password']);
        } else {
            $validated['password'] = Hash::make($validated['password']);
        }

        $validated['is_active'] = $request->boolean('is_active');
        $user->update($validated);
        ActivityLog::record('update_user', "User '{$user->name}' diperbarui", $user);

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $this->gate();
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat menghapus akun Anda sendiri.');
        }
        $name = $user->name;
        $user->delete();
        ActivityLog::record('delete_user', "User '{$name}' dihapus");
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }
}
