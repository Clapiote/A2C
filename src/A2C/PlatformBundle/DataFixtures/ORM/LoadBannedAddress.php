<?php
// src/A2C/PlatformBundle/DataFixtures/ORM/LoadBannedAddress.php

namespace A2C\PlatformBundle\Fixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use A2C\PlatformBundle\Entity\BannedAddress;

/**
 * Provides some bannedAddress objects
 *
 * @author Vincent
 */
class LoadBannedAddress implements FixtureInterface {
    
    public function load(ObjectManager $manager)
    {
        $addresses = array(
            'hello@evil.com',
            'spam@evil.com',
            'zorglub@spam.com'
        );
        
        foreach($addresses as $address) {
            $b = new BannedAddress();
            $b->setemailAddress($address);
            
            $manager->persist($b);
        }
        
        $manager->flush();
    }
    
}
