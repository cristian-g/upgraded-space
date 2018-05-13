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

    public function getAll($userId) {
        try {
            $stmt = $this->connection->prepare("
                (SELECT 
                notification.id, 
                notification.id_share, 
                notification.type, 
                notification.message, 
                notification.created_at, 
                notification.updated_at
                FROM notification
                INNER JOIN share ON share.id = notification.id_share
                INNER JOIN upload ON share.id_upload = upload.id
                WHERE upload.id_user = :id AND 
                (notification.type = 'folder_sended' OR 
                notification.type = 'upload_renamed' OR
                notification.type = 'upload_deleted' OR 
                notification.type = 'new_upload'))
                UNION
                (SELECT 
                notification.id, 
                notification.id_share, 
                notification.type, 
                notification.message, 
                notification.created_at, 
                notification.updated_at
                FROM notification
                INNER JOIN share ON share.id = notification.id_share
                WHERE share.id_user_destination = :id AND 
                notification.type = 'folder_received')
                ORDER BY
                created_at DESC
            ");
            $stmt->bindParam('id', $userId);
            $stmt->execute();
            return $stmt->fetchAll();
        }
        catch (\Doctrine\DBAL\DBALException $e) {
            return $e->getMessage();
        }
    }

    public function getLastFive($userId) {
        try {
            $stmt = $this->connection->prepare("
                (SELECT 
                notification.id, 
                notification.id_share, 
                notification.type, 
                notification.message, 
                notification.created_at, 
                notification.updated_at
                FROM notification
                INNER JOIN share ON share.id = notification.id_share
                INNER JOIN upload ON share.id_upload = upload.id
                WHERE upload.id_user = :id AND 
                (notification.type = 'folder_sended' OR 
                notification.type = 'upload_renamed' OR
                notification.type = 'upload_deleted' OR 
                notification.type = 'new_upload'))
                UNION
                (SELECT 
                notification.id, 
                notification.id_share, 
                notification.type, 
                notification.message, 
                notification.created_at, 
                notification.updated_at
                FROM notification
                INNER JOIN share ON share.id = notification.id_share
                WHERE share.id_user_destination = :id AND 
                notification.type = 'folder_received')
                ORDER BY
                created_at DESC
                LIMIT 5
            ");
            $stmt->bindParam('id', $userId);
            $stmt->execute();
            return $stmt->fetchAll();
        }
        catch (\Doctrine\DBAL\DBALException $e) {
            return $e->getMessage();
        }
    }
}
