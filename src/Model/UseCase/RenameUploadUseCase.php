<?php

namespace Pwbox\Model\UseCase;

use Pwbox\Model\Upload;
use Pwbox\Model\UploadRepository;

class RenameUploadUseCase
{
    /** @var UploadRepository */
    private $repository;

    /**
     * PostUploadUseCase constructor.
     * @param UploadRepository $repository
     */
    public function __construct(UploadRepository $repository)
    {
        $this->repository = $repository;
    }


    public function __invoke(array $rawData)
    {
        $now = new \DateTime('now');
        $upload = new Upload(
            $rawData['id'],
            null,
            null,
            null,
            $rawData['name'],
            null,
            null,
            null,
            $now
        );
        return $this->repository->rename($upload);
    }
}