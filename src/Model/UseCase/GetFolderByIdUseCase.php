<?php

namespace Pwbox\Model\UseCase;

use Pwbox\Model\UploadRepository;

class GetFolderByIdUseCase
{
    /** @var UploadRepository */
    private $repository;

    /**
     * GetFolderByIdUseCase constructor.
     * @param UploadRepository $repository
     */
    public function __construct(UploadRepository $repository)
    {
        $this->repository = $repository;
    }
    
    public function __invoke($uuid)
    {
        return $this->repository->getById($uuid);
    }
}