<?php

interface UserRepositoryInterface
{
    public function create(User $user);
    public function read($id);
    public function update(User $user);
    public function delete($id);
    public function readAll();
    public function exists(User $user);
}
?>