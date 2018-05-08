<?php

namespace Pwbox\Model\UseCase;

use Pwbox\Model\Upload;
use Pwbox\Model\UploadRepository;

class PostFileUseCase
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
            null,
            $_SESSION['user_id'],
            $rawData['filename'],
            $rawData['extension'],
            $now,
            $now
        );
        return $this->repository->save($upload);
    }
}