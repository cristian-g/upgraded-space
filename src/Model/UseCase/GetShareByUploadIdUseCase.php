<?php

namespace Pwbox\Model\UseCase;

use Pwbox\Model\ShareRepository;

class GetShareByUploadIdUseCase
{
    /** @var ShareRepository */
    private $repository;

    /**
     * GetShareByUploadIdUseCase constructor.
     * @param ShareRepository $repository
     */
    public function __construct(ShareRepository $repository)
    {
        $this->repository = $repository;
    }
    
    public function __invoke($uploadId, $userId)
    {
        return $this->repository->getByUploadId($uploadId, $userId);
    }
}