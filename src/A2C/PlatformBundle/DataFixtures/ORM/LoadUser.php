<?php
// src/A2C/PlatformBundle/DataFixtures/ORM/LoadUser.php

namespace A2C\PlatformBundle\Fixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use A2C\PlatformBundle\Entity\User;

/**
 * Provides some users
 *
 * @author Vincent
 */
class LoadUser extends Fixture {

    public function load(ObjectManager $manager)
    {
        $user1 = new User("Alexandre", "Bananier", "ab@aol.fr", User::$genderType["male"]);
        $user2 = new User("Babou", "Badadoum", "baba@yahoo.fr", User::$genderType["female"]);
        $user3 = new User("Claude", "Badass", "cb@visa.com", User::$genderType["male"]);
        $user4 = new User("Mathilde", "D", "yo@mathildeetvincent.fr", User::$genderType["female"]);
        $users = [$user1, $user2, $user3, $user4];
        
        $i = 1;
        foreach ($users as $u) {
            $this->addReference("user$i", $u);
            $manager->persist($u);
            $i++;
        }
                
        $manager->flush();
    }
}
