<?php

namespace Pwbox\Model\UseCase;

use Pwbox\Model\UploadRepository;

class DeleteUploadUseCase
{
    /** @var UploadRepository */
    private $repository;

    /**
     * DeleteUploadUseCase constructor.
     * @param UploadRepository $repository
     */
    public function __construct(UploadRepository $repository) {
        $this->repository = $repository;
    }

    public function __invoke($id) {
        $this->repository->delete($id);
    }
}