<?php

namespace Pwbox\Model;

class Notification {
    private $id;
    private $idShare;
    private $type;
    private $message;
    private $createdAt;
    private $updatedAt;

    /**
     * Notification constructor.
     * @param $id
     * @param $idShare
     * @param $type
     * @param $message
     * @param $createdAt
     * @param $updatedAt
     */
    public function __construct($id, $idShare, $type, $message, $createdAt, $updatedAt)
    {
        $this->id = $id;
        $this->idShare = $idShare;
        $this->type = $type;
        $this->message = $message;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getIdShare()
    {
        return $this->idShare;
    }

    /**
     * @param mixed $idShare
     */
    public function setIdShare($idShare)
    {
        $this->idShare = $idShare;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param mixed $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    public static function fromArray($array)
    {
        $object = new Notification(
            $array["id"],
            $array["id_share"],
            $array["type"],
            $array["message"],
            $array["created_at"],
            $array["updated_at"]
        );
        return $object;
    }
}
