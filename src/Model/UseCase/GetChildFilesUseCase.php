<?php

namespace Pwbox\Model\UseCase;

use Pwbox\Model\UploadRepository;

class GetChildFilesUseCase {
    /** @var UploadRepository */
    private $repository;

    /**
     * GetChildFilesUseCase constructor.
     * @param UploadRepository $repository
     */
    public function __construct(UploadRepository $repository) {
        $this->repository = $repository;
    }

    public function __invoke($id) {
        return $this->repository->getChildFiles($id);
    }
}