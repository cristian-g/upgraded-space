<?php

namespace Pwbox\Model\UseCase;

use Pwbox\Model\User;
use Pwbox\Model\UserRepository;

class PostUserUseCase
{
    /** @var UserRepository */
    private $repository;

    /**
     * PostUserUseCase constructor.
     * @param UserRepository $repository
     */
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }


    public function __invoke(array $rawData)
    {
        $now = new \DateTime('now');
        $user = new User(
            null,
            $rawData['username'],
            $rawData['email'],
            $rawData['birthdate'],
            $rawData['password'],
            $now,
            $now
        );
        $this->repository->save($user);
    }
}