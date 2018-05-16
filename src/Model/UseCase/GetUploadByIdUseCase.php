<?php

namespace Pwbox\Model\UseCase;

use Pwbox\Model\UploadRepository;

class GetUploadByIdUseCase
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
    
    public function __invoke($id)
    {
        return $this->repository->getById($id);
    }
}