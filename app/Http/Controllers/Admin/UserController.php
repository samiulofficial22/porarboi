<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('role', 'user')->latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->load(['orders' => function ($q) {
            $q->with('book')->latest();
        }]);

        $totalSpent = $user->orders()->where('status', 'approved')->sum('amount');
        $totalOrders = $user->orders()->count();

        return view('admin.users.show', compact('user', 'totalSpent', 'totalOrders'));
    }

    public function destroy(User $user)
    {
        $user->delete();
        return back()->with('success', 'User deleted successfully');
    }
}
