<?php
// src/A2C/PlatformBundle/Entity/Advert.php

namespace A2C\PlatformBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Advert
 *
 * @ORM\Table(name="a2c_advert")
 * @ORM\Entity(repositoryClass="A2C\PlatformBundle\Repository\AdvertRepository")
 */
class Advert
{
    /**
     * @var int The id of the advert, auto-generated.
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string The city in which will take place the meetup.
     *
     * @ORM\Column(name="city", type="string", length=64)
     */
    private $city;

    /**
     * @var string The department of the city, auto-calculated.
	 * @TODO : auto calculate this
     *
     * @ORM\Column(name="dept", type="string", length=64)
     */
    private $dept;

    /**
     * @var \DateTime The date of the advert's creation.
     *
     * @ORM\Column(name="creationDate", type="datetime")
     */
    private $creationDate;

    /**
     * @var string The text of the advert.
     *
     * @ORM\Column(name="text", type="text")
     */
    private $text;

    /**
     * @var bool True if the advert is old and archived.
     *
     * @ORM\Column(name="isArchived", type="boolean")
     */
    private $isArchived;

    /**
     * @var enum Used by $purpose variable
     */
    public static $purposeType = array("teacher"=>0, "learner"=>1);
    
    /**
     * @var int 0 if it's a teacher advert, 1 if it's a learner/sharer advert.
     * Prefer the use of $purposeType enumeration
     *
     * @ORM\Column(name="purpose", type="smallint")
     */
    private $purpose;

    /**
     * @var type The user who created the advert.
     * Advert is the owner of the relationship 
     * @ORM\ManyToOne(targetEntity="A2C\PlatformBundle\Entity\User", inversedBy="adverts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;
    
    /**
     * Constructor
     */
    public function __construct($city = null, $dept = null, $purpose = null, $text = null) {
        $this->city = $city;
        $this->dept = $dept;
        $this->creationDate = new \DateTime("now", new \DateTimeZone('Europe/Paris'));
        $this->purpose = $purpose;
        $this->text = $text;
        $this->isArchived = false;
        $this->user = null;
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
     * Set city
     *
     * @param string $city
     *
     * @return Advert
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set dept
     *
     * @param string $dept
     *
     * @return Advert
     */
    public function setDept($dept)
    {
        $this->dept = $dept;

        return $this;
    }

    /**
     * Get dept
     *
     * @return string
     */
    public function getDept()
    {
        return $this->dept;
    }

    /**
     * Set creationDate
     *
     * @param \DateTime $creationDate
     *
     * @return Advert
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    /**
     * Get creationDate
     *
     * @return \DateTime
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * Set text
     *
     * @param string $text
     *
     * @return Advert
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set isArchived
     *
     * @param boolean $isArchived
     *
     * @return Advert
     */
    public function setIsArchived($isArchived)
    {
        $this->isArchived = $isArchived;

        return $this;
    }

    /**
     * Get isArchived
     *
     * @return bool
     */
    public function getIsArchived()
    {
        return $this->isArchived;
    }

    /**
     * Set purpose
     *
     * @param integer $purpose
     *
     * @return Advert
     */
    public function setPurpose($purpose)
    {
        $this->purpose = $purpose;

        return $this;
    }

    /**
     * Get purpose
     *
     * @return int
     */
    public function getPurpose()
    {
        return $this->purpose;
    }
	
    /**
    * Set user
    *
    * @param User $user
    *
    * @return Advert
    */
    public function setUser($user)
    {
            $this->user = $user;
            $user->addAdvert($this);
            return $this;
    }

    /**
    * Get user
    *
    * @return User
    */
    public function getUser()
    {
            return $this->user;
    }
	
	
}
