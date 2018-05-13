<?php

namespace Pwbox\Model\UseCase;

use Pwbox\Model\NotificationRepository;

class GetNotificationsUseCase
{
    /** @var NotificationRepository */
    private $repository;

    /**
     * GetNotificationsUseCase constructor.
     * @param NotificationRepository $repository
     */
    public function __construct(NotificationRepository $repository) {
        $this->repository = $repository;
    }

    public function __invoke($id) {
        return $this->repository->getAll($id);
    }
}