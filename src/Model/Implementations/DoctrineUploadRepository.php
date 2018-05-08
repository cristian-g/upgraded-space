<?php

namespace Pwbox\Model\Implementations;

use Doctrine\DBAL\Connection;
use Pwbox\Model\Upload;
use Pwbox\Model\UploadRepository;

class DoctrineUploadRepository implements UploadRepository
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
     * @param Upload $upload
     * @return mixed
     * @throws \Doctrine\DBAL\DBALException
     */
    public function save(Upload $upload)
    {
        $sql = "INSERT INTO upload(uuid, id_user, name, ext, created_at, updated_at) VALUES(uuid(), :id_user, :name, :ext, :created_at, :updated_at)";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("id_user", $upload->getIdUser(), 'string');
        $stmt->bindValue("name", $upload->getName(), 'string');
        $stmt->bindValue("ext", $upload->getExt(), 'string');
        $stmt->bindValue("created_at", $upload->getCreatedAt()->format(self::DATE_FORMAT));
        $stmt->bindValue("updated_at", $upload->getCreatedAt()->format(self::DATE_FORMAT));
        $stmt->execute();
        // Save id
        $upload->setId($this->connection->lastInsertId());
        // Return id
        return $upload->getId();
    }

    public function get($id) {
        try {
            $array = $this->connection->fetchAssoc('SELECT id, uuid, id_user, name, ext, created_at, updated_at FROM upload WHERE id = ? LIMIT 1', array($id));
            $upload = Upload::fromArray($array);
            return $upload;
        }
        catch (\Doctrine\DBAL\DBALException $e) {
            return $e->getMessage();
        }
    }

    public function getAll($id) {
        try {
            $stmt = $this->connection->prepare('SELECT id, uuid, id_user, name, ext, created_at, updated_at FROM upload WHERE id_user = :id');
            $stmt->bindParam('id', $id);
            $result = $stmt->execute();
            return $stmt->fetchAll();
        }
        catch (\Doctrine\DBAL\DBALException $e) {
            return $e->getMessage();
        }
    }
}
