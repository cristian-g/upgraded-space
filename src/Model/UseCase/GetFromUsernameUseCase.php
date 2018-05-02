<?php
/**
 * Created by PhpStorm.
 * User: marc
 * Date: 5/2/18
 * Time: 1:30 PM
 */

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