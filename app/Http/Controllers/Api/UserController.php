<?php
namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Menampilkan semua user
    public function index()
    {
        $users = User::with('roles')->get();
        return new UserResource(
            true, // Status
            'Users retrieved successfully', // Message
            $users // Resource (data)
        );
    }

    // Menyimpan data user baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|exists:roles,id', // Validasi role
        ]);

        // Buat user baru
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Lampirkan role ke user
        $user->roles()->attach($request->role);

        return new UserResource(
            true, // Status
            'User created successfully', // Message
            $user // Resource (data)
        );
    }

    // Menampilkan data user berdasarkan id
    public function show($id)
    {
        $user = User::with('roles')->findOrFail($id);
        return new UserResource(
            true, // Status
            'User retrieved successfully', // Message
            $user // Resource (data)
        );
    }

    // Update data user
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6',
            'role' => 'nullable|exists:roles,id', // Validasi role opsional
        ]);

        $user = User::findOrFail($id);
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
        ]);

        // Jika role diberikan, perbarui
        if ($request->has('role')) {
            $user->roles()->sync([$request->role]);
        }

        return new UserResource(
            true, // Status
            'User updated successfully', // Message
            $user // Resource (data)
        );
    }

    // Menghapus user
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return new UserResource(
            true, // Status
            'User deleted successfully', // Message
            null // Resource (data)
        );
    }
}
