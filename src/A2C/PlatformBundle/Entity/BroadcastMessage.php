<?php

// src/A2C/PlatformBundle/Entity/BroadcastMessage.php

namespace A2C\PlatformBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * This object is used to build the contact form, when an admin wants to send a
 * message to all the users.
 *
 * @TODO add the users which the mail is sent to.
 * 
 * @ORM\Table(name="a2c_brodcastMessage")
 * @ORM\Entity(repositoryClass="A2C\PlatformBundle\Repository\BroadCastMessageRepository")
 * @author Vincent
 */
class BroadcastMessage
{

    /**
     * @var int The id of the broadcast message, auto-generated.
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * The date of the sending
     * @var type date
     * @ORM\Column(name="date", type="date")
     * @Assert\NotBlank
     * @Assert\DateTime()
     */
    private $date;

    /**
     * The message
     * @var type string
     */
    private $message;

    /**
     * The message's title
     * @var type string
     */
    private $title;
    
    public function __construct()
    {
        $this->date = new \DateTime("now", new \DateTimeZone('Europe/Paris'));
    }

    /**
     * Set date
     *
     * @param date $date
     *
     * @return BroadcastMessage
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return date
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set message
     *
     * @param string $message
     *
     * @return BroadcastMessage
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set message's title
     *
     * @param string $title
     *
     * @return BroadcastMessage
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get message's title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

}
