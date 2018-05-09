<?php

namespace Pwbox\Model\UseCase;

use Pwbox\Model\UploadRepository;

class GetFolderByUuidUseCase
{
    /** @var UploadRepository */
    private $repository;

    /**
     * GetFolderByUuidUseCase constructor.
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