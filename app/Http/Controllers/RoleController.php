<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    public function index()
    {
        // $users = User::all();
        // $roles = Role::all();

        return view('role.index', [
            // 'users' => $users,
            // 'roles' => $roles,
            'roles' => Role::all(),
            'side'  => 'role',
        ]);
    }

    public function data()
    {
        $users = User::all()->map(function ($user) {

            $role = $user->getRoleNames()->first() ?? '';

            return [

                'id'    => $user->id,

                'name'  => $user->name,

                'email' => $user->email,

                'role'  => $role,

                'action' => '<button onclick=\'editRole('.$user->id.', '.json_encode($user->name).', '.json_encode($role).')\' class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-lg">Edit</button>',
            ];
        });

        return response()->json($users);
    }


    // CREATE ROLE BARU
    public function storeRole(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name'
        ]);

        Role::create([
            'name' => $request->name
        ]);

        return response()->json([
            'message' => 'Role berhasil dibuat'
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'role' => 'required'
        ]);

        $user = User::findOrFail($id);

        // hapus role lama
        $user->syncRoles([]);

        // assign role baru
        $user->assignRole($request->role);

        return response()->json([
            'message' => 'Role berhasil diupdate',
        ]);
    }
}
