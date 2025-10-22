<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use App\Models\Computer;
use App\Models\Company;
use App\Models\Department;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function index()
    {
        $query = User::with('company');
        if (request('search')) {
            $search = strtolower(request('search'));
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(name) LIKE ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(email) LIKE ?', ["%{$search}%"]);
            });
        }
        if (request('role')) {
            $query->where('role', request('role'));
        }
        if (request('status')) {
            $query->where('status', request('status'));
        }

        $users = $query->paginate(10)->appends(request()->query());
        return view('admin.users.index', compact('users'));
    }
    public function create()
    {
        $computers = Computer::all();
        $companies = Company::all();
        $departments = Department::all();
        return view('admin.users.create', compact('computers', 'companies', 'departments'));
    }
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'username'      => 'required|string|max:255|unique:users,username',
            'email'         => 'required|email|max:255|unique:users,email',
            'password'      => 'required|string|confirmed|min:8',
            'role'          => ['required', Rule::in(['admin', 'employee', 'agent'])],
            'id_employee'   => 'nullable|string|max:255',
            'department_id' => 'nullable|exists:departments,id',
            'hire_date'     => 'nullable|date',
            'is_online'     => 'nullable|in:0,1',
            'status'        => ['required', Rule::in(['active', 'inactive', 'suspended'])],
            'empresa_id'    => 'nullable|exists:companies,id',
        ]);

        if (isset($data['is_online'])) {
            $data['is_online'] = (bool) $data['is_online'];
        }

        $user = new User(collect($data)->except('password')->toArray());
        $user->password = Hash::make($data['password']);
        $user->save();

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuario creado correctamente.');
    }
    public function edit(User $user)
    {
        $companies = Company::all();
        $departments = Department::all();
        $computers = Computer::all();
        return view('users.edit', compact('user', 'companies', 'departments', 'computers'));
    }
    public function show($id)
    {
        $user = User::with('pc')->findOrFail($id);
        return view('users.show', compact('user'));
    }
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'username'      => ['required', 'string', 'max:255', Rule::unique('users', 'username')->ignore($user->id)],
            'email'         => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password'      => 'nullable|string|confirmed|min:8',
            'role'          => ['required', Rule::in(['admin', 'employee', 'agent'])],
            'id_employee'   => 'nullable|string|max:255',
            'department_id' => 'nullable|exists:departments,id',
            'hire_date'     => 'nullable|date',
            'is_online'     => 'nullable|in:0,1',
            'empresa_id'    => 'nullable|exists:companies,id',
            'computers'     => 'nullable|array',
            'computers.*'   => 'exists:computers,id',
        ]);

        $fieldsToAudit = ['name', 'department_id', 'password'];
        $original = $user->getOriginal();
        foreach ($fieldsToAudit as $field) {
            if ($field === 'password' && !empty($data['password'])) {
                $oldValue = '***';
                $newValue = '***';
                if (!Hash::check($data['password'], $user->password)) {
                    \App\Models\UserChangeLog::create([
                        'user_id' => $user->id,
                        'changed_by' => auth()->id(),
                        'field' => 'password',
                        'old_value' => $oldValue,
                        'new_value' => $newValue,
                    ]);
                }
                $user->password = Hash::make($data['password']);
            } elseif ($field !== 'password' && isset($data[$field]) && $data[$field] != $original[$field]) {
                \App\Models\UserChangeLog::create([
                    'user_id' => $user->id,
                    'changed_by' => auth()->id(),
                    'field' => $field,
                    'old_value' => $original[$field],
                    'new_value' => $data[$field],
                ]);
            }
        }
        if (isset($data['is_online'])) {
            $data['is_online'] = (bool) $data['is_online'];
        }
        $user->fill(collect($data)->except(['password', 'computers'])->toArray());
        $user->save();
        $selectedComputers = $request->input('computers', []);
        $desasignados = Computer::where('assigned_user_id', $user->id)
            ->whereNotIn('id', $selectedComputers)
            ->update(['assigned_user_id' => null]);
        $asignados = Computer::whereIn('id', $selectedComputers)
            ->update(['assigned_user_id' => $user->id]);
        return redirect()->route('admin.users.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuario eliminado correctamente.');
    }
}
