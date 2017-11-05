<?php
// src/A2C/PlatformBundle/Entity/BannedAddress.php

namespace A2C\PlatformBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * This class is used to manage banned email addresses that are not linked with
 * an user. It happens when someone responds to an advert.
 * A BannedAddress object is created by the admin interface.
 * 
 * @author Vincent
 */

/**
 * @ORM\Table(name="a2c_bannedAddress")
 * @ORM\Entity(repositoryClass="A2C\PlatformBundle\Repository\BannedAddressRepository")
 */
class BannedAddress {
    /**
     * @var int The id in database
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @var string The email address that is banned
     * @ORM\Column(name="emailAddress", type="string", length=255)
     */
    private $emailAddress;
    
    /**
     * @var date The date at which the address was banned
     * @ORM\Column(name="bannedDate", type="date")
     */
    private $bannedDate;
    
    public function __construct()
    {
        $this->bannedDate = new \Datetime();
    }
    
    /**
     * @return int
     */
    public function getId() 
    {
        return $this->id;
    }
    
    /**
     * @param int id
     */
    public function setId($id)
    {
        $this->id = $id;
    }
    
    /**
     * @return string email address
     */
    public function getEmailAddress()
    {
        return $this->emailAddress;
    }

    /*
     * @param string email address
     */
    public function setEmailAddress($emailAddress)
    {
        $this->emailAddress = $emailAddress;
    }
    
    /**
     * @return date banned date
     */
    public function getBannedDate()
    {
        return $this->bannedDate;
    }

    /*
     * @param date banned date
     */
    public function setBannedDate($bannedDate)
    {
        $this->bannedDate = $bannedDate;
    }
}
