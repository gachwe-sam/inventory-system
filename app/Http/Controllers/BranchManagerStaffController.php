<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Support\StockPermissions;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BranchManagerStaffController extends Controller
{
    public function index()
    {
        $staff = User::where('branch_id', auth()->user()->branch_id)
            ->where('id', '!=', auth()->id())
            ->orderBy('name')
            ->get();

        return view('manager.staff.index', compact('staff'));
    }

    public function edit(User $user)
    {
        $this->authorizeSameBranch($user);

        $permissions = StockPermissions::MANAGER_ASSIGNABLE;

        return view('manager.staff.edit', compact('user', 'permissions'));
    }

    public function update(Request $request, User $user)
    {
        $this->authorizeSameBranch($user);

        $validated = $request->validate([
            'permissions' => 'array',
            'permissions.*' => Rule::in(StockPermissions::MANAGER_ASSIGNABLE),
        ]);

        $untouchable = $user->permissions()
            ->whereNotIn('name', StockPermissions::MANAGER_ASSIGNABLE)
            ->pluck('name')
            ->all();

        $user->syncPermissions(array_merge($untouchable, $validated['permissions'] ?? []));

        return redirect()->route('manager.staff.index')->with('success', 'Permissions updated.');
    }

    private function authorizeSameBranch(User $user): void
    {
        abort_unless(
            $user->branch_id !== null && (int) $user->branch_id === (int) auth()->user()->branch_id,
            403
        );
    }
}
