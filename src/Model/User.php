<?php
/**
 * Created by PhpStorm.
 * User: marc
 * Date: 4/12/18
 * Time: 8:19 PM
 */

namespace Pwbox\Model;


class User
{
    private $id;
    private $username;
    private $password;
    private $email;
    private $createdAt;
    private $updateAt;

    /**
     * User constructor.
     * @param $id
     * @param $username
     * @param $password
     * @param $email
     * @param $createdAt
     * @param $updateAt
     */
    public function __construct($id, $username, $password, $email, $createdAt, $updateAt)
    {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
        $this->createdAt = $createdAt;
        $this->updateAt = $updateAt;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return mixed
     */
    public function getUpdateAt()
    {
        return $this->updateAt;
    }




}