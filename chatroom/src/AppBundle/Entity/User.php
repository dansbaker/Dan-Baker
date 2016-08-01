<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User
{	
	 /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $user_id;

    /** 
      * @ORM\Column(type="string", length=64) 
      */
    private $email_address;

     /** 
      * @ORM\Column(type="string", length=32) 
      */
    private $password_hash;

    private $entityManager;

    public function __construct(\Doctrine\ORM\EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    public function findById($user_id)
    {
        $user = $this->entityManager->find('\AppBundle\Entity\User', $user_id);
        if(!$user instanceof \AppBundle\Entity\User)
        {
            throw new \Exception('Unknown User ID in postMessage'); 
        }
        return $user;
    }
    
    /**
     * Get userId
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Set emailAddress
     *
     * @param string $emailAddress
     *
     * @return User
     */
    public function setEmailAddress($emailAddress)
    {
        $this->email_address = $emailAddress;

        return $this;
    }

    /**
     * Get emailAddress
     *
     * @return string
     */
    public function getEmailAddress()
    {
        return $this->email_address;
    }

    /**
     * Set passwordHash
     *
     * @param string $passwordHash
     *
     * @return User
     */
    public function setPasswordHash($passwordHash)
    {
        $this->password_hash = $passwordHash;

        return $this;
    }

    /**
     * Get passwordHash
     *
     * @return string
     */
    public function getPasswordHash()
    {
        return $this->password_hash;
    }
}
