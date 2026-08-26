<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Support\StockPermissions;

class UserController extends Controller
{
    public function showRegistrationForm()
    {
        return view('quick.register');
    }

    public function showLoginForm()
    {
        return view('quick.login');
    }

    public function register(Request $request)
    {
        $incomingFields = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('users', 'name')],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => ['required', 'string', 'min:8', 'confirmed'],

        ]);

        $incomingFields['password'] = bcrypt($incomingFields['password']);
        $user = User::create($incomingFields);
        $this->logUserIn($request, $user);
        return redirect('/')->with('success', 'Account created and logged in!');
    }

    public function login(Request $request, OtpController $otp)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (!auth()->attempt($credentials)) {
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])->onlyInput('email');
        }

        // Password checks out, but the session isn't fully trusted until the OTP step passes.
        $user = auth()->user();
        auth()->logout();

        return $otp->issueOtp($request, $user);
    }

    public function logout(Request $request)
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('success', 'You have been logged out.');
    }

    public function index()
{
    $users = User::with(['branch', 'roles'])->orderBy('name')->paginate(14);
    return view('users.index', compact('users'));
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
