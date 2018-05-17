<?php
namespace Pwbox\Model\UseCase;
use Pwbox\Model\User;
use Pwbox\Model\UserRepository;
class EditUserUseCase
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
    public function __invoke(array $rawData, $value, $extension, int $uid)
    {
        $now = new \DateTime('now');
        $user = new User(
            $uid,
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
        //if we update the profile picture
        if ($value == 0) {
            return $this->repository->updateWithPicture($user);
        }
        else{
            return $this->repository->update($user);
        }
    }
}