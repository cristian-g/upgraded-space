<?php

namespace Pwbox\Model\Implementations;

use Doctrine\DBAL\Connection;
use Pwbox\Model\Share;
use Pwbox\Model\ShareRepository;

class DoctrineShareRepository implements ShareRepository
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
     * @param Share $share
     * @return mixed
     * @throws \Doctrine\DBAL\DBALException
     */
    public function save(Share $share)
    {
        $sql = "INSERT INTO share(id_upload, id_user_destination, role, created_at, updated_at) VALUES(:id_upload, :id_user_destination, :role, :created_at, :updated_at)";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("id_upload", $share->getIdUpload(), 'integer');
        $stmt->bindValue("id_user_destination", $share->getIdUserDestination(), 'integer');
        $stmt->bindValue("role", $share->getRole(), 'string');
        $stmt->bindValue("created_at", $share->getCreatedAt()->format(self::DATE_FORMAT));
        $stmt->bindValue("updated_at", $share->getUpdatedAt()->format(self::DATE_FORMAT));
        $stmt->execute();
        // Save id
        $share->setId($this->connection->lastInsertId());
        // Return id
        return $share->getId();
    }

    public function getAll($userId) {
        try {
            $stmt = $this->connection->prepare('SELECT id, id_upload, id_user_destination, role, created_at, updated_at FROM share WHERE id_user_destination = :id');
            $stmt->bindParam('id', $userId);
            $stmt->execute();
            return $stmt->fetchAll();
        }
        catch (\Doctrine\DBAL\DBALException $e) {
            return $e->getMessage();
        }
    }
}
