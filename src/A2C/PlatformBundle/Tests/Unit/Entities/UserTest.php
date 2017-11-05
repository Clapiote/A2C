<?php
// src/A2C/PlatformBundle/Test/Unit/Entities/UserTest.php

namespace A2C\PlatformBundle\Tests\Unit\Entities;

use A2C\PlatformBundle\Entity\User;
use A2C\PlatformBundle\Entity\Advert;
use PHPUnit\Framework\TestCase;

/**
 * Description of UserTest
 *
 * @author Vincent
 */
final class UserTest extends TestCase 
{
    
    private $user = null;
    
    public function setUp() 
    {
        parent::setUp();
        $this->user = new User("PrenomTest", "NomTest", "mail@test.org", User::$genderType["male"]);
    }
    
    /**
     * Test if advertNb behave correctly when adding an advert
     */
    public function testAddAdvert()
    {
        $advertNb = $this->user->getAdvertsNb();
        $advert = new Advert("Grenoble", "Isère", Advert::$purposeType["teacher"], "Je donne dé cour 2 francé lol");
        $advert->setUser($this->user);
        
        $this->assertEquals($advertNb+1, $this->user->getAdvertsNb());
    }
    
    /**
     * Test if advertNb behave correctly when removing an advert
     */
    public function testRemoveAdvert()
    {
        
        $advert = new Advert("Grenoble", "Isère", Advert::$purposeType["teacher"], "Je donne dé cour 2 francé lol");
        $advert->setUser($this->user);
        $advertNb = $this->user->getAdvertsNb();
        
        $this->user->removeAdvert($advert);
        $this->assertEquals($advertNb-1, $this->user->getAdvertsNb());
    }
}
