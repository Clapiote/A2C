<?php
// src/A2C/PlatformBundle/DataFixtures/ORM/LoadAdvert.php

namespace A2C\PlatformBundle\Fixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use A2C\PlatformBundle\Entity\User;
use A2C\PlatformBundle\Entity\Advert;

/**
 * Description of LoadAdvert
 *
 * @author Vincent
 */
class LoadAdvert extends Fixture {
    
    public function load(ObjectManager $manager)
    {
        $advert1 = new Advert("Grenoble", "Isère", Advert::$purposeType["teacher"], "Je donne dé cour 2 francé lol");
        $advert1->setUser($this->getReference('user1')); 
        
        $advert2 = new Advert("Lannion", "Côtes d'armor", Advert::$purposeType["learner"], "Je voudrais apprendre le breton, ils ne parlent que ça ici.");
        $advert2->setUser($this->getReference('user2')); 
        
		$advert3 = new Advert("Nice", "Alpes-maritimes", Advert::$purposeType["teacher"], "Cours de pissaladière");
        $advert3->setUser($this->getReference('user1')); 
        
        $advert4 = new Advert("Nice", "Alpes-maritimes", Advert::$purposeType["learner"], "Moi vouloir apprendre italien");
        $advert4->setUser($this->getReference('user3')); 
		
		$advert5 = new Advert("Auch", "Alpes-maritimes", Advert::$purposeType["learner"], "Je voudrais apprendre à prononcer le nom de ma ville");
        $advert5->setUser($this->getReference('user3')); 
		
        $adverts = [$advert1, $advert2, $advert3, $advert4, $advert5];
        
        foreach ($adverts as $a) {
            $manager->persist($a);
        }
        
        $manager->flush();
        
    }
    
    public function getDependencies()
    {
        return array(
            LoadUser::class,
        );
    }
}
