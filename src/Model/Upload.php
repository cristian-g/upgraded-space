<?php

namespace Pwbox\Model;

class Upload
{
    private $id;
    private $uuid;
    private $idUser;
    private $idParent;
    private $name;
    private $ext;
    private $bytesSize;
    private $createdAt;
    private $updatedAt;

    /**
     * Upload constructor.
     * @param $id
     * @param $uuid
     * @param $idUser
     * @param $idParent
     * @param $name
     * @param $ext
     * @param $bytesSize
     * @param $createdAt
     * @param $updatedAt
     */
    public function __construct($id, $uuid, $idUser, $idParent, $name, $ext, $bytesSize, $createdAt, $updatedAt)
    {
        $this->id = $id;
        $this->uuid = $uuid;
        $this->idUser = $idUser;
        $this->idParent = $idParent;
        $this->name = $name;
        $this->ext = $ext;
        $this->bytesSize = $bytesSize;
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
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * @param mixed $uuid
     */
    public function setUuid($uuid)
    {
        $this->uuid = $uuid;
    }

    /**
     * @return mixed
     */
    public function getIdUser()
    {
        return $this->idUser;
    }

    /**
     * @param mixed $idUser
     */
    public function setIdUser($idUser)
    {
        $this->idUser = $idUser;
    }

    /**
     * @return mixed
     */
    public function getIdParent()
    {
        return $this->idParent;
    }

    /**
     * @param mixed $idParent
     */
    public function setIdParent($idParent)
    {
        $this->idParent = $idParent;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getExt()
    {
        return $this->ext;
    }

    /**
     * @param mixed $ext
     */
    public function setExt($ext)
    {
        $this->ext = $ext;
    }

    /**
     * @return mixed
     */
    public function getBytesSize()
    {
        return $this->bytesSize;
    }

    /**
     * @param mixed $bytesSize
     */
    public function setBytesSize($bytesSize)
    {
        $this->bytesSize = $bytesSize;
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
        $object = new Upload(
            $array["id"],
            $array["uuid"],
            $array["id_user"],
            $array["id_parent"],
            $array["name"],
            $array["ext"],
            $array["bytes_size"],
            $array["created_at"],
            $array["updated_at"]
        );
        $object->setUuid($array["uuid"]);
        return $object;
    }
}
