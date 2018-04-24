<?php

namespace Pwbox\Model\Implementations;

use Doctrine\DBAL\Connection;
use Pwbox\Model\User;
use Pwbox\Model\UserRepository;

class DoctrineUserRepository implements UserRepository
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
     * @param User $user
     * @throws \Doctrine\DBAL\DBALException
     */
    public function save(User $user)
    {
        $sql = "INSERT INTO user(username, email, birthdate, password, active, email_activation_key, created_at, updated_at) VALUES(:username, :email, :birthdate, :password, :active, :email_activation_key, :created_at, :updated_at)";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("username", $user->getUsername(), 'string');
        $stmt->bindValue("email", $user->getEmail(), 'string');
        $stmt->bindValue("birthdate", $user->getBirthdate(), 'string');
        $stmt->bindValue("password", $user->getPassword(), 'string');
        $stmt->bindValue("active", $user->getActive(), 'string');
        $stmt->bindValue("email_activation_key", $user->getEmailActivationKey(), 'string');
        $stmt->bindValue("created_at", $user->getCreatedAt()->format(self::DATE_FORMAT));
        $stmt->bindValue("updated_at", $user->getCreatedAt()->format(self::DATE_FORMAT));
        $stmt->execute();
        // Save id
        $user->setId($this->connection->lastInsertId());
        $_SESSION["user_id"] = $user->getId();
    }

    public function get($id) {
        try {
            $array = $this->connection->fetchAssoc('SELECT id, username, email, birthdate, password, active, email_activation_key, created_at, updated_at FROM user WHERE id = ? LIMIT 1', array($_SESSION["user_id"]));
            $user = User::fromArray($array);
            return $user;
        }
        catch (\Doctrine\DBAL\DBALException $e) {

        }
    }

}