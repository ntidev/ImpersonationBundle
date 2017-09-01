<?php

namespace NTI\ImpersonationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entity: ImpersonationKey
 *
 * @ORM\Table(name="nti_impersonation_key")
 * @ORM\Entity
 */
class ImpersonationKey {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="impersonation_key", type="string", length=255)
     */
    private $key;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expires", type="datetime")
     */
    private $expires;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return ImpersonationKey
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return ImpersonationKey
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param string $key
     * @return ImpersonationKey
     */
    public function setKey($key)
    {
        $this->key = $key;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getExpires()
    {
        return $this->expires;
    }

    /**
     * @param DateTime $expires
     * @return ImpersonationKey
     */
    public function setExpires($expires)
    {
        $this->expires = $expires;
        return $this;
    }
}