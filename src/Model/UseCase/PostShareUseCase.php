<?php

namespace Pwbox\Model\UseCase;

use Pwbox\Model\Share;
use Pwbox\Model\ShareRepository;

class PostShareUseCase
{
    /** @var ShareRepository */
    private $repository;

    /**
     * PostShareUseCase constructor.
     * @param ShareRepository $repository
     */
    public function __construct(ShareRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(array $rawData)
    {
        $now = new \DateTime('now');
        $share = new Share(
            null,
            $rawData['idUpload'],
            $rawData['idUserDestination'],
            $rawData['role'],
            $now,
            $now
        );
        return $this->repository->save($share);
    }
}