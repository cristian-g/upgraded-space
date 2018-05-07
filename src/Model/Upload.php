<?php

namespace Pwbox\Model;

class Upload
{
    private $id;
    private $idUser;
    private $name;
    private $ext;
    private $createdAt;
    private $updatedAt;

    /**
     * Upload constructor.
     * @param $id
     * @param $idUser
     * @param $name
     * @param $ext
     * @param $createdAt
     * @param $updatedAt
     */
    public function __construct($id, $idUser, $name, $ext, $createdAt, $updatedAt)
    {
        $this->id = $id;
        $this->idUser = $idUser;
        $this->name = $name;
        $this->ext = $ext;
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
}
