<?php

namespace Pwbox\Model\UseCase;

use Pwbox\Model\UploadRepository;

class GetFileUseCase
{
    /** @var UploadRepository */
    private $repository;

    /**
     * GetFileUseCase constructor.
     * @param UploadRepository $repository
     */
    public function __construct(UploadRepository $repository)
    {
        $this->repository = $repository;
    }


    public function __invoke($id)
    {
        return $this->repository->get($id);

    }
}