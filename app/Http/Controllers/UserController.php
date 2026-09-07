<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Support\StockPermissions;

class UserController extends Controller
{
    public function index()
    {
        return view('users.index');
    }

    public function data(Request $request)
    {
        $users = User::with(['branch', 'roles'])
            ->orderBy('name')
            ->paginate($request->integer('size', 15));

        $rows = collect($users->items())->map(fn (User $user) => [
            'name' => $user->name,
            'email' => $user->email,
            'branch' => $user->branch->name ?? 'Head Office / Unassigned',
            'role' => $user->roles->pluck('name')->join(', ') ?: 'No role',
            'actions_html' => view('users.partials.actions', compact('user'))->render(),
        ]);

        return response()->json(['data' => $rows, 'last_page' => $users->lastPage()]);
    }

public function edit(User $user)
{
    $branches = \App\Models\Branch::orderBy('name')->get();
    $roles = \Spatie\Permission\Models\Role::orderBy('name')->get();
    $permissions = StockPermissions::ALL;
    return view('users.edit', compact('user', 'branches', 'roles', 'permissions'));
}

public function update(Request $request, User $user)
{
    $validated = $request->validate([
        'branch_id' => 'nullable|exists:branches,id',
        'role' => 'required|exists:roles,name',
        'permissions' => 'array',
        'permissions.*' => Rule::in(StockPermissions::ALL),
    ]);

    $user->update(['branch_id' => $validated['branch_id']]);
    $user->syncRoles([$validated['role']]);
    $user->syncPermissions($validated['permissions'] ?? []);

    return redirect()->route('users.index')->with('success', 'User updated.');
}

}
