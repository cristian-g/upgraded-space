<?php

namespace Pwbox\Model;

interface UserRepository
{
    public function save(User $user);
    public function get($id);
}