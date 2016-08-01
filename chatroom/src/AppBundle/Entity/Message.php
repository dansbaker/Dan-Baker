<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use \AppBundle\Entity\User;
use JsonSerializable;

/**
 * @ORM\Entity
 * @ORM\Table(name="messages")
 */
class Message implements JsonSerializable
{
     /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $message_id;

     /** 
     * @ORM\ManyToOne(targetEntity="User" ) 
     * @ORM\JoinColumn(name="user_id", referencedColumnName="user_id")
     */
     private $user;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

     /** 
      * @ORM\Column(type="datetime") 
      */
    private $timestamp;

     /**
     * Create a new message instance
     */
    public function __construct($user, $content)
    {
        $this->user = $user;
        $this->content = $content;
        $this->timestamp = new \DateTime();
    }

    /**
    *
    */
    public function jsonSerialize() {
        return Array('content' => $this->content,
                         'timestamp' => $this->timestamp->format('Y-m-d H:i:s'),
                         'email_address' => $this->user->getEmailAddress());
        
    }

    /**
     * Get messageId
     *
     * @return integer
     */
    public function getMessageId()
    {
        return $this->message_id;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Message
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set timestamp
     *
     * @param \DateTime $timestamp
     *
     * @return Message
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    /**
     * Get timestamp
     *
     * @return \DateTime
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Message
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
