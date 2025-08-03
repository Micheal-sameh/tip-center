<?php

namespace App\Repositories;

use App\Enums\UserStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class UserRepository extends BaseRepository
{
    public function __construct(User $model)
    {
        $this->model = $model;
    }

    protected function model(): string
    {
        return User::class;
    }

    public bool $pagination = true;

    public int $perPage = 10;

    protected function execute(Builder $query): Collection|LengthAwarePaginator
    {
        return $this->pagination ? $query->paginate($this->perPage) : $query->get();
    }

    public function index()
    {
        $query = $this->model->query();

        return $this->execute($query);
    }

    public function show($id)
    {
        return $this->findById($id);
    }

    public function store($input)
    {
        $password = 'tip_family';
        DB::beginTransaction();
        $user = $this->model->create([
            'name' => $input->name,
            'email' => $input->email,
            'password' => bcrypt($password),
            'phone' => $input->phone,
            'status' => 1,
            'birth_date' => $input->birth_date,
        ]);
        $role = Role::find($input->role_id);
        $user->assignRole($role);
        DB::commit();

        return $user;
    }

    public function update($input, $id)
    {
        $user = $this->findById($id);
        $user->update([
            'name' => $input->name ?? $user->name,
            'email' => $input->email ?? $user->email,
            'phone' => $input->phone ?? $user->phone,
            'status' => $input->status ?? $user->status,
        ]);
        $currentRole = $user->roles->first();
        if ($currentRole->id != $input->role_id) {
            $user->removeRole($currentRole);
            $role = Role::find($input->role_id);
            $user->assignRole($role);
        }

        return $user;
    }

    public function delete($id)
    {
        $user = $this->findById($id);
        $user->delete();
    }

    public function changeStatus($id)
    {
        $user = $this->findById($id);
        $user->update([
            'status' => $user->status == UserStatus::ACTIVE ? UserStatus::INACTIVE : UserStatus::ACTIVE,
        ]);

        return $user;
    }

    public function profilePic($image, $id)
    {
        $user = $this->findById($id);

        $user->clearMediaCollection('profile_pic');
        $user->addMedia($image)
            ->toMediaCollection('profile_pic');

        return $user;
    }
}
