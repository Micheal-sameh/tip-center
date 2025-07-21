<?php

namespace App\Http\Controllers;

use App\DTOs\UserDTO;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Services\UserService;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct(protected UserService $userService)
    {
        $this->middleware('permission:users_view')->only(['index', 'show']);
        $this->middleware('permission:users_create')->only(['create', 'store']);
        $this->middleware('permission:users_update')->only(['edit', 'update']);
        $this->middleware('permission:users_delete')->only('destroy');
        $this->middleware('permission:users_resetPassword')->only('resetPassword');
    }

    public function index()
    {
        $users = $this->userService->index();

        return view('users.index', compact('users'));
    }

    public function show($id)
    {
        $user = $this->userService->show($id);

        return view('users.show', compact('user'));
    }

    public function create()
    {
        $roles = Role::where('name', '!=', 'student')->get();

        return view('users.create', compact('roles'));
    }

    public function store(UserCreateRequest $request)
    {
        $input = new UserDTO(...$request->only(
            'name', 'email', 'role_id', 'birth_date', 'phone'
        ));
        $this->userService->store($input);

        return to_route('users.index');
    }

    public function edit($id)
    {
        $user = $this->userService->show($id);
        $roles = Role::where('name', '!=', 'student')->get();

        return view('users.edit', compact('user', 'roles'));
    }

    public function update(UserUpdateRequest $request, $id)
    {
        $input = new UserDTO(...$request->only(
            'email', 'role_id', 'birth_date', 'phone'
        ));

        $this->userService->update($input, $id);

        return to_route('users.show', $id);
    }

    public function delete($id)
    {
        $this->userService->delete($id);

        return to_route('users.index');
    }
}
