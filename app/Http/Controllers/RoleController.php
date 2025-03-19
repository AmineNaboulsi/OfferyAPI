<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;

class RoleController extends Controller
{
    public function index()
    {
        return response()->json([
            'roles' => Role::all()
        ]);
    }
    public function store(StoreRoleRequest $request)
    {
        try {
            $role = Role::create([
                "name" => $request->name
            ]);
            return response()->json([
                "message" => "role created",
                'role' => $role
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while creating the role',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function update(UpdateRoleRequest $request, Role $role)
    {
        $role->update([
            "name" => $request->name
        ]);
        return response()->json([
            "message" => "role updated",
            'role' => $role
        ]);
    }
    public function destroy(Role $role)
    {
        $role->delete();
        return response()->json([
            "message" => "role deleted",
        ]);
    }
}
