<?php

namespace Pwbox\Model\UseCase;

use Pwbox\Model\UploadRepository;

class GetUploadsUseCase
{
    /** @var UploadRepository */
    private $repository;

    /**
     * GetUploadsUseCase constructor.
     * @param UploadRepository $repository
     */
    public function __construct(UploadRepository $repository) {
        $this->repository = $repository;
    }


    public function __invoke($folderId = null, $userId = null) {
        return $this->repository->getAll($folderId, $userId);
    }
}