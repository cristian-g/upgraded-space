<?php

namespace Pwbox\Model\UseCase;

use Pwbox\Model\ShareRepository;

class GetSharesUseCase
{
    /** @var ShareRepository */
    private $repository;

    /**
     * GetSharesUseCase constructor.
     * @param ShareRepository $repository
     */
    public function __construct(ShareRepository $repository) {
        $this->repository = $repository;
    }

    public function __invoke($id) {
        return $this->repository->getAll($id);
    }
}