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
     * @throws \Doctrine\DBAL\DBALException
     */
    public function save(Upload $upload)
    {
        $sql = "INSERT INTO upload(id_user, name, ext, created_at, updated_at) VALUES(:id_user, :name, :ext, :created_at, :updated_at)";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("id_user", $upload->getIdUser(), 'string');
        $stmt->bindValue("name", $upload->getName(), 'string');
        $stmt->bindValue("ext", $upload->getExt(), 'string');
        $stmt->bindValue("created_at", $upload->getCreatedAt()->format(self::DATE_FORMAT));
        $stmt->bindValue("updated_at", $upload->getCreatedAt()->format(self::DATE_FORMAT));
        $stmt->execute();
    }
}
