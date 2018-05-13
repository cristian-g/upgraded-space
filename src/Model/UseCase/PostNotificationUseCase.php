<?php

namespace Pwbox\Model\UseCase;

use Pwbox\Model\Notification;
use Pwbox\Model\NotificationRepository;

class PostNotificationUseCase
{
    /** @var NotificationRepository */
    private $repository;

    /**
     * PostNotificationUseCase constructor.
     * @param NotificationRepository $repository
     */
    public function __construct(NotificationRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(array $rawData)
    {
        $now = new \DateTime('now');
        $notification = new Notification(
            null,
            $rawData['idShare'],
            $rawData['type'],
            $rawData['message'],
            $now,
            $now
        );
        return $this->repository->save($notification);
    }
}