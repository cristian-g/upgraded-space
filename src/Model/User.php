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
    private $email;
    private $birthdate;
    private $password;
    private $active;
    private $emailActivationKey;
    private $createdAt;
    private $updatedAt;

    /**
     * User constructor.
     * @param $id
     * @param $username
     * @param $password
     * @param $email
     * @param $createdAt
     * @param $updateAt
     */
    public function __construct($id, $username, $email, $birthdate, $password, $createdAt, $updatedAt)
    {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->birthdate = $birthdate;
        $this->password = password_hash($password, PASSWORD_DEFAULT, ['cost' => 11]);
        $this->active = 0;
        $this->emailActivationKey = md5($email . $username);
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
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getBirthdate()
    {
        return $this->birthdate;
    }

    /**
     * @param mixed $birthdate
     */
    public function setBirthdate($birthdate)
    {
        $this->birthdate = $birthdate;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @param mixed $active
     */
    public function setActive($active)
    {
        $this->active = $active;
    }

    /**
     * @return mixed
     */
    public function getEmailActivationKey()
    {
        return $this->emailActivationKey;
    }

    /**
     * @param mixed $emailActivationKey
     */
    public function setEmailActivationKey($emailActivationKey)
    {
        $this->emailActivationKey = $emailActivationKey;
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
     * @param mixed $updateAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updateAt = $updatedAt;
    }

    public static function fromArray($array)
    {
        $object = new User($array["id"], $array["username"], $array["email"], $array["birthdate"], $array["password"], $array["created_at"], $array["updated_at"]);
        return $object;
    }

    public function toArray() {
        $array = [];
        $array["id"] = $this->getId();
        $array["username"] = $this->getUsername();
        $array["email"] = $this->$this->getEmail();
        $array["birthdate"] = $this->getBirthdate();
        $array["password"] = $this->password;
        $array["created_at"] = $this->getCreatedAt();
        $array["updated_at"] = $this->getUpdateAt();
        return $array;
    }
}