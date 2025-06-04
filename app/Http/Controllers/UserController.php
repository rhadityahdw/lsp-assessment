<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Http\Requests\UserRequest;
use App\Services\UserService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class UserController extends Controller
{
    public function __construct(
        protected UserService $userService
    ) {
        $this->middleware('permission:view user')->only(['index', 'show']);
        $this->middleware('permission:create user')->only(['create', 'store']);
        $this->middleware('permission:edit user')->only(['edit', 'update']);
        $this->middleware('permission:delete user')->only(['destroy']);
    }

    public function index()
    {
        $users = $this->userService->getAllUsers();

        return Inertia::render('users/Index', [
            'users' => $users,
            'permissions' => [
                'create' => Auth::user()->can('view user'), // Admin only
                'edit' => Auth::user()->can('edit user'), // Admin only
                'delete' => Auth::user()->can('delete user'), // Admin only
            ]
        ]);
    }

    public function create()
    {
        $roles = Role::pluck('name');

        return Inertia::render('users/Create', [
            'roles' => $roles,
        ]);
    }

    public function store(UserRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole($request->role_name);

        return redirect()->route('users.index')
            ->with('success', 'User created successfully');
    }

    public function show(User $user)
    {
        return Inertia::render('users/Show', [
            'user' => $user->load('roles'),
            'roles' => Role::pluck('name'),
        ]);
    }

    public function edit(User $user)
    {
        return Inertia::render('users/Edit', [
            'user' => $user->load('roles'),
            'roles' => Role::pluck('name'),
        ]);
    }

    public function update(UserRequest $request, User $user)
    {
        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);
        $user->syncRoles([$request->role_name]);

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully');
    }

    public function destroy(User $user)
    {
        $user->roles()->detach();
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully');
    }
}
