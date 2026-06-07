<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{

  public function index(Request $request)
{
    $search = $request->search;

    $users = User::when($search, function ($query, $search) {
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%$search%")
              ->orWhere('staff_id', 'like', "%$search%")
              ->orWhere('email', 'like', "%$search%");
        });
    })
    ->orderBy('name', 'asc')
    ->paginate(10);

    return view('head.usermanagement', compact('users'));
}


    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $user->update([
            'role' => $request->role,
            'status' => $request->status
        ]);

        return back()->with('success', 'User updated successfully');
    }
}
