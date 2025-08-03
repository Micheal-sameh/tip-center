<?php

namespace App\Services;

use App\Repositories\UserRepository;

class UserService
{
    public function __construct(protected UserRepository $userRepository) {}

    public function index()
    {
        $users = $this->userRepository->index();
        $users->load('roles');

        return $users;
    }

    public function show($id)
    {
        $user = $this->userRepository->show($id);
        $user->load('roles');

        return $user;
    }

    public function store($input)
    {
        $user = $this->userRepository->store($input);
        $user->load('roles');

        return $user;
    }

    public function update($input, $id)
    {
        $user = $this->userRepository->update($input, $id);
        $user->load('roles');

        return $user;
    }

    public function delete($id)
    {
        return $this->userRepository->delete($id);
    }

    public function changeStatus($id)
    {
        return $this->userRepository->changeStatus($id);
    }

    public function profilePic($image, $id)
    {
        return $this->userRepository->profilePic($image, $id);
    }

    public function updatePassword($password)
    {
        $user = $this->userRepository->updatePassword($password);
        $user->load('roles');

        return $user;
    }
}
