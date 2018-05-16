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
    public function __invoke(array $rawData, $value, $extension)
    {
        $now = new \DateTime('now');
        $user = new User(
            null,
            null,
            $rawData['username'],
            $rawData['email'],
            $rawData['birthdate'],
            $rawData['password'],
            0,
            $now,
            $now,
            $value,
            $extension
        );
        return $this->repository->save($user);
    }
}