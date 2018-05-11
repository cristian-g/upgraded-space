<?php

namespace Pwbox\Model\UseCase;

use Pwbox\Model\UploadRepository;

class GetFolderSizeUseCase
{
    /** @var UploadRepository */
    private $repository;

    /**
     * GetFolderSizeUseCaseUseCase constructor.
     * @param UploadRepository $repository
     */
    public function __construct(UploadRepository $repository)
    {
        $this->repository = $repository;
    }
    
    public function __invoke($folderId)
    {
        return $this->repository->getFolderSizeInBytes($folderId);
    }
}