<?php

namespace Pwbox\Model;

interface UserRepository
{
    public function save(User $user);
    public function get($id);
    public function getFromEmail($email);
    public function getFromUsername($username);
    public function activate(User $user);
    public function getByEmailActivationKeyUseCase($emailActivationKey);
}