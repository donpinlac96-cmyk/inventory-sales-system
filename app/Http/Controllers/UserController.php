<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('name')->paginate(10);

        return view('users.index', compact('users'));
    }

    public function update(Request $request, User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot change your own role.');
        }

        $validated = $request->validate([
            'role' => ['required', Rule::in(['admin', 'cashier'])],
        ]);

        if ($user->role === 'admin' && $validated['role'] === 'cashier') {
            $adminCount = User::where('role', 'admin')->count();

            if ($adminCount <= 1) {
                return back()->with('error', 'You cannot demote the last administrator.');
            }
        }

        $user->role = $validated['role'];
        $user->save();

        return redirect()->route('users.index')
            ->with('success', 'User role updated successfully!');
    }

    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        if ($user->role === 'admin') {
            $adminCount = User::where('role', 'admin')->count();

            if ($adminCount <= 1) {
                return back()->with('error', 'You cannot delete the last administrator.');
            }
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User account deleted successfully!');
    }
}
