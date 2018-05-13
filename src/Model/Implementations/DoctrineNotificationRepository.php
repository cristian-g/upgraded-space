<?php

namespace Pwbox\Model\Implementations;

use Doctrine\DBAL\Connection;
use Pwbox\Model\Notification;
use Pwbox\Model\NotificationRepository;

class DoctrineNotificationRepository implements NotificationRepository
{
    private const DATE_FORMAT = 'Y-m-d H:i:s';

    /** @var Connection */
    private $connection;

    /**
     * DoctrineUserRepository constructor.
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param Notification $notification
     * @return mixed
     * @throws \Doctrine\DBAL\DBALException
     */
    public function save(Notification $notification)
    {
        $sql = "INSERT INTO notification(id_share, type, message, created_at, updated_at) VALUES(:id_share, :type, :message, :created_at, :updated_at)";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("id_share", $notification->getIdShare(), 'integer');
        $stmt->bindValue("type", $notification->getType(), 'string');
        $stmt->bindValue("message", $notification->getMessage(), 'string');
        $stmt->bindValue("created_at", $notification->getCreatedAt()->format(self::DATE_FORMAT));
        $stmt->bindValue("updated_at", $notification->getUpdatedAt()->format(self::DATE_FORMAT));
        $stmt->execute();
        // Save id
        $notification->setId($this->connection->lastInsertId());
        // Return id
        return $notification->getId();
    }
}
