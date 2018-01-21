<?php

// src/A2C/PlatformBundle/Entity/BroadcastMessage.php

namespace A2C\PlatformBundle\Entity;

/**
 * This object is used to build the contact form, when an admin wants to send a
 * message to all the users.
 *
 * @author Vincent
 */
class BroadcastMessage
{

    /**
     * The date of the sending
     * @var type date
     */
    private $date;

    /**
     * The message
     * @var type string
     */
    private $message;

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

}
