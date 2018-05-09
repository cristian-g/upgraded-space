<?php

namespace Pwbox\Model\UseCase;

use Pwbox\Model\UploadRepository;

class GetFolderUseCase
{
    /** @var UploadRepository */
    private $repository;

    /**
     * GetFolderUseCase constructor.
     * @param UploadRepository $repository
     */
    public function __construct(UploadRepository $repository)
    {
        $this->repository = $repository;
    }
    
    public function __invoke($uuid)
    {
        return $this->repository->getByUuid($uuid);
    }
}