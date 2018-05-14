<?php

namespace Pwbox\Model\UseCase;

use Pwbox\Model\UserRepository;

class GetUserByEmailActivationKeyUseCase
{
    /** @var UserRepository */
    private $repository;

    /**
     * GetUserByEmailActivationKeyUseCase constructor.
     * @param UserRepository $repository
     */
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke($emailActivationKey)
    {
        return $this->repository->getByEmailActivationKeyUseCase($emailActivationKey);

    }
}