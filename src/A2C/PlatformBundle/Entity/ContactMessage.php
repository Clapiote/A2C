<?php

// src/A2C/PlatformBundle/Entity/ContactMessage.php

namespace A2C\PlatformBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * This object is used to build the contact form, when someone wants to send
 * a message to the admin of the website.
 *
 * @author Vincent
 */
class ContactMessage
{

    /**
     * The name of the sender
     * @var type string
     */
    private $name;

    /**
     * The email address of the sender
     * @var type string
     * @Assert\Email(message="contact.errors.bad_email_address")
     */
    private $emailAddress;

    /**
     * The message
     * @var type string
     */
    private $message;

    public static function fromArray($array)
    {
        $cm = new ContactMessage();
        $cm->setName($array['name']);
        $cm->setEmailAddress($array['emailAddress']);
        $cm->setMessage($array['message']);
        return $cm;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return ContactMessage
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set emailAddress
     *
     * @param string $emailAddress
     *
     * @return ContactMessage
     */
    public function setEmailAddress($emailAddress)
    {
        $this->emailAddress = $emailAddress;

        return $this;
    }

    /**
     * Get emailAddress
     *
     * @return string
     */
    public function getEmailAddress()
    {
        return $this->emailAddress;
    }

    /**
     * Set message
     *
     * @param string $message
     *
     * @return ContactMessage
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
