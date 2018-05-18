<?php

namespace Pwbox\Model\UseCase;

use Pwbox\Model\UserRepository;

class DeleteUserUseCase
{
    /** @var UserRepository */
    private $repository;

    /**
     * DeleteUserUseCase constructor.
     * @param UserRepository $repository
     */
    public function __construct(UserRepository $repository) {
        $this->repository = $repository;
    }

    public function __invoke(int $userid) {
        $this->repository->delete($userid);
    }
}