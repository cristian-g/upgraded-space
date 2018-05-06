<?php
/**
 * Created by PhpStorm.
 * User: Pau
 * Date: 3/5/2018
 * Time: 19:35
 */

namespace Pwbox\Model\UseCase;


use Pwbox\Model\UserRepository;

class DeleteUserUseCase
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


    public function __invoke(array $userid)
    {
        //TODO Eliminar documents i informació de l'usuari abans de borrar-los
        $this->repository->delete($userid);
    }
}