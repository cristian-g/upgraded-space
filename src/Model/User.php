<?php
namespace Pwbox\Model;
class User
{
    private $id;
    private $uuid;
    private $username;
    private $email;
    private $birthdate;
    private $password;
    private $active;
    private $emailActivationKey;
    private $createdAt;
    private $updatedAt;
    private $default_profile;
    private $extension;
    /**
     * User constructor.
     * @param $id
     * @param $uuid
     * @param $username
     * @param $email
     * @param $birthdate
     * @param $password
     * @param $active
     * @param $emailActivationKey
     * @param $createdAt
     * @param $updatedAt
     * @param $default_profile
     * @param $extension
     */
    public function __construct($id, $uuid, $username, $email, $birthdate, $password, $active, $createdAt, $updatedAt, $default_profile, $extension)
    {
        $this->id = $id;
        $this->uuid = $uuid;
        $this->username = $username;
        $this->email = $email;
        $this->birthdate = $birthdate;
        $this->password = password_hash($password, PASSWORD_DEFAULT, ['cost' => 11]);
        $this->active = $active;
        $this->emailActivationKey = md5($email . $username);
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->default_profile = $default_profile;
        $this->extension = $extension;
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
     * @param mixed $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }
    /**
     * @return mixed
     */
    public function getDefaultProfile()
    {
        return $this->default_profile;
    }
    /**
     * @param mixed $default_profile
     */
    public function setDefaultProfile($default_profile)
    {
        $this->default_profile = $default_profile;
    }
    /**
     * @return mixed
     */
    public function getExtension()
    {
        return $this->extension;
    }
    /**
     * @param mixed $extension
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;
    }
    public static function fromArray($array)
    {
        $object = new User(
            $array["id"],
            $array["uuid"],
            $array["username"],
            $array["email"],
            $array["birthdate"],
            null,
            $array["active"],
            $array["created_at"],
            $array["updated_at"],
            $array["default_picture"],
            $array["extension"]);
        $object->setPassword($array["password"]);
        return $object;
    }
    public function toArray() {
        $array = [];
        $array["id"] = $this->getId();
        $array["uuid"] = $this->getUuid();
        $array["username"] = $this->getUsername();
        $array["email"] = $this->getEmail();
        $array["birthdate"] = $this->getBirthdate();
        $array["password"] = $this->getPassword();
        $array["created_at"] = $this->getCreatedAt();
        $array["updated_at"] = $this->getUpdatedAt();
        $array["default_profile"] = $this->getDefaultProfile();
        $array["extension"] = $this->getExtension();
        return $array;
    }
}