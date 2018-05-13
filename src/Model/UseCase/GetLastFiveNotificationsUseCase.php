<?php

namespace Pwbox\Model\UseCase;

use Pwbox\Model\NotificationRepository;

class GetLastFiveNotificationsUseCase
{
    /** @var NotificationRepository */
    private $repository;

    /**
     * GetLastFiveNotificationsUseCase constructor.
     * @param NotificationRepository $repository
     */
    public function __construct(NotificationRepository $repository) {
        $this->repository = $repository;
    }

    public function __invoke($id) {
        return $this->repository->getLastFive($id);
    }
}