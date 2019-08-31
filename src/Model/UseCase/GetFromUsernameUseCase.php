<?php

namespace Pwbox\Model\UseCase;

use Pwbox\Model\User;
use Pwbox\Model\UserRepository;

class GetFromUsernameUseCase
{
    /** @var UserRepository */
    private $repository;

    /**
     * GetFromUsernameUseCase constructor.
     * @param UserRepository $repository
     */
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }


    public function __invoke($username)
    {
        return $this->repository->getFromUsername($username);

    }
}