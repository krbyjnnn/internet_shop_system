<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = User::where('role', 'customer')->get();
        return view('admin.customers.index', compact('customers'));
    }

    public function create()
    {
        return view('admin.customers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|unique:users', 
            'password' => 'required|min:6',
        ]);

        User::create([
            'name'     => $request->name,
            'username' => $request->username, 
            'password' => bcrypt($request->password),
            'role'     => 'customer',
            'balance'  => 0,
        ]);

        return redirect()->route('admin.customers.index')
            ->with('success', 'Customer created successfully!');
    }

    public function topup(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount'  => 'required|numeric|min:1',
        ]);

        $user = User::find($request->user_id);
        $user->increment('balance', $request->amount);

        return redirect()->route('admin.topup')
            ->with('success', "₱{$request->amount} topped up to {$user->username} successfully!");
    }

    public function topupForm()
    {
        $customers = User::where('role', 'customer')->get();
        return view('admin.topup', compact('customers'));
    }
}