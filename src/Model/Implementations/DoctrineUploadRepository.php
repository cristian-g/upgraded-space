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
        $sql = "INSERT INTO upload(uuid, id_user, id_parent, name, ext, bytes_size, created_at, updated_at) VALUES(uuid(), :id_user, :id_parent, :name, :ext, :bytes_size, :created_at, :updated_at)";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("id_user", $upload->getIdUser(), 'integer');
        $stmt->bindValue("id_parent", $upload->getIdParent(), 'integer');
        $stmt->bindValue("name", $upload->getName(), 'string');
        $stmt->bindValue("ext", $upload->getExt(), 'string');
        $stmt->bindValue("bytes_size", $upload->getBytesSize(), 'integer');
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
            $array = $this->connection->fetchAssoc('SELECT id, uuid, id_user, id_parent, name, ext, bytes_size, created_at, updated_at FROM upload WHERE id = ? LIMIT 1', array($id));
            $upload = Upload::fromArray($array);
            return $upload;
        }
        catch (\Doctrine\DBAL\DBALException $e) {
            return $e->getMessage();
        }
    }

    public function getByUuid($uuid) {
        try {
            $array = $this->connection->fetchAssoc('SELECT id, uuid, id_user, id_parent, name, ext, bytes_size, created_at, updated_at FROM upload WHERE uuid = ? LIMIT 1', array($uuid));
            $upload = Upload::fromArray($array);
            return $upload;
        }
        catch (\Doctrine\DBAL\DBALException $e) {
            return $e->getMessage();
        }
    }

    public function getAll($id, $folderId = null) {
        try {
            if ($folderId == null) {
                $stmt = $this->connection->prepare('SELECT id, uuid, id_user, id_parent, name, ext, bytes_size, created_at, updated_at FROM upload WHERE id_user = :id AND id_parent IS NULL');
                $stmt->bindParam('id', $id);
            }
            else {
                $stmt = $this->connection->prepare('SELECT id, uuid, id_user, id_parent, name, ext, bytes_size, created_at, updated_at FROM upload WHERE id_user = :id AND id_parent = :id_parent');
                $stmt->bindParam('id', $id);
                $stmt->bindParam('id_parent', $folderId);
            }
            $result = $stmt->execute();
            return $stmt->fetchAll();
        }
        catch (\Doctrine\DBAL\DBALException $e) {
            return $e->getMessage();
        }
    }
}
