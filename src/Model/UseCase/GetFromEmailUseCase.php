<?php

namespace Pwbox\Model\UseCase;

use Pwbox\Model\UserRepository;

class GetFromEmailUseCase
{
    /** @var UserRepository */
    private $repository;

    /**
     * GetFromEmailUseCase constructor.
     * @param UserRepository $repository
     */
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke($email)
    {
        return $this->repository->getFromEmail($email);

    }
}