<?php

namespace App\Http\Controllers;

use App\DTOs\UserDTO;
use App\Http\Requests\ProfilePicRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct(protected UserService $userService)
    {
        $this->middleware('permission:users_view')->only(['index', 'show']);
        $this->middleware('permission:users_create')->only(['create', 'store']);
        $this->middleware('permission:users_update')->only(['edit', 'update']);
        $this->middleware('permission:users_delete')->only('delete');
        $this->middleware('permission:users_changeStatus')->only('changeStatus');
        $this->middleware('permission:users_resetPassword')->only('resetPassword');
        $this->middleware('permission:users_profile_pic')->only('profilePic');
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

    public function changeStatus($id)
    {
        $user = $this->userService->changeStatus($id);

        return response()->json([
            'success' => true,
            'message' => __('messages.Status updated'),
        ]);
    }

    public function profilePic(ProfilePicRequest $request, $id)
    {
        $user = $this->userService->profilePic($request->image, $id);

        return redirect()->back()->with('success', 'Profile picture updated successfully');
    }

    public function profile()
    {
        $user = $this->userService->show(Auth::id());

        return view('users.profile', compact('user'));
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        $user = $this->userService->updatePassword($request->new_password);

        return to_route('users.profile')->with('success', 'Password updated successfully');
    }

    public function resetPassword($id)
    {
        $user = $this->userService->resetPassword($id);

        return to_route('users.index')->with('success', 'Password updated successfully');
    }
}
