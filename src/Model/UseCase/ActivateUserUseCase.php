<?php

namespace Pwbox\Model\UseCase;

use Pwbox\Model\User;
use Pwbox\Model\UserRepository;

class ActivateUserUseCase
{
    /** @var UserRepository */
    private $repository;

    /**
     * ActivateUserUseCase constructor.
     * @param UserRepository $repository
     */
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(User $user) {
        return $this->repository->activate($user);
    }
}