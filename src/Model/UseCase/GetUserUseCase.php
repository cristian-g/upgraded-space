<?php

namespace Pwbox\Model\UseCase;

use Pwbox\Model\User;
use Pwbox\Model\UserRepository;

class GetUserUseCase
{
    /** @var UserRepository */
    private $repository;

    /**
     * GetUserUseCase constructor.
     * @param UserRepository $repository
     */
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }


    public function __invoke($id)
    {
        return $this->repository->get($id);

    }
}