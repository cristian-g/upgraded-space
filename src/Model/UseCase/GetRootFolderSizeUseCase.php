<?php

namespace Pwbox\Model\UseCase;

use Pwbox\Model\UploadRepository;

class GetRootFolderSizeUseCase
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
    
    public function __invoke($userId)
    {
        return $this->repository->getRootFolderSizeInBytes($userId);
    }
}