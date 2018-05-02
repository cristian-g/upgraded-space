<?php
/**
 * Created by PhpStorm.
 * User: marc
 * Date: 5/2/18
 * Time: 12:22 PM
 */

namespace Pwbox\Model\UseCase;

use Pwbox\Model\User;
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