<?php

namespace Pwbox\Model;

class Share {
    private $id;
    private $idUpload;
    private $idUserDestination;
    private $role;
    private $createdAt;
    private $updatedAt;

    /**
     * Share constructor.
     * @param $id
     * @param $idUpload
     * @param $idUserDestination
     * @param $role
     * @param $createdAt
     * @param $updatedAt
     */
    public function __construct($id, $idUpload, $idUserDestination, $role, $createdAt, $updatedAt)
    {
        $this->id = $id;
        $this->idUpload = $idUpload;
        $this->idUserDestination = $idUserDestination;
        $this->role = $role;
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
    public function getIdUpload()
    {
        return $this->idUpload;
    }

    /**
     * @param mixed $idUpload
     */
    public function setIdUpload($idUpload)
    {
        $this->idUpload = $idUpload;
    }

    /**
     * @return mixed
     */
    public function getIdUserDestination()
    {
        return $this->idUserDestination;
    }

    /**
     * @param mixed $idUserDestination
     */
    public function setIdUserDestination($idUserDestination)
    {
        $this->idUserDestination = $idUserDestination;
    }

    /**
     * @return mixed
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param mixed $role
     */
    public function setRole($role)
    {
        $this->role = $role;
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
        $object = new Share(
            $array["id"],
            $array["id_upload"],
            $array["id_user_destination"],
            $array["role"],
            $array["created_at"],
            $array["updated_at"]
        );
        return $object;
    }
}
