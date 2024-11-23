<?php
namespace App\Http\Controllers\Api;

use App\Models\Role;
use App\Http\Controllers\Controller;
use App\Http\Resources\RoleResource;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    // Menampilkan semua roles
    public function index()
    {
        $roles = Role::all(); // Get all roles

        // Return roles as a collection of RoleResources with custom message and status
        return new RoleResource(
            true, // Status
            'Roles retrieved successfully', // Message
            $roles // Resource (data)
        );
    }

    // Menyimpan data role baru
    public function store(Request $request)
    {
        // Validate input data
        $request->validate([
            'name' => 'required|string|unique:roles,name', // Ensure unique name for the role
        ]);

        // Create a new role
        $role = Role::create([
            'name' => $request->name,
        ]);

        // Return newly created role as a resource with status and message
        return new RoleResource(
            true, // Status
            'Role created successfully', // Message
            $role // Resource (data)
        );
    }

    // Menampilkan role berdasarkan ID
    public function show($id)
    {
        $role = Role::findOrFail($id); // Find role by ID

        // Return the role as a resource with custom message and status
        return new RoleResource(
            true, // Status
            'Role retrieved successfully', // Message
            $role // Resource (data)
        );
    }

    // Update data role
    public function update(Request $request, $id)
    {
        // Validate input data
        $request->validate([
            'name' => 'required|string|unique:roles,name,' . $id, // Ensure unique name, excluding the current role ID
        ]);

        $role = Role::findOrFail($id); // Find role by ID

        // Update role
        $role->update([
            'name' => $request->name,
        ]);

        // Return updated role as a resource with status and message
        return new RoleResource(
            true, // Status
            'Role updated successfully', // Message
            $role // Resource (data)
        );
    }

    // Menghapus role
    public function destroy($id)
    {
        $role = Role::findOrFail($id); // Find role by ID
        $role->delete(); // Delete the role

        // Return success response with null data
        return new RoleResource(
            true, // Status
            'Role deleted successfully', // Message
            null // Resource (data)
        );
    }
}
