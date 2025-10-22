<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Department;
use App\Models\Company;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show($id)
    {
        $user = User::with(['department', 'company', 'pc', 'tickets'])->findOrFail($id);
        return view('users.show', compact('user'));
    }
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $departments = Department::all();
        $companies = Company::all();
        return view('users.edit', compact('user', 'departments', 'companies'));
    }
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'department_id' => 'nullable|exists:departments,id',
            'empresa_id' => 'nullable|exists:companies,id',
            'role' => 'required|in:admin,agent,employee',
            'status' => 'required|in:active,inactive,suspended',
        ]);
        $user->update($data);
        return redirect()->route('agent.users.show', $user->id)
            ->with('success', 'Usuario actualizado correctamente.');
    }
}
