<?php

// src/A2C/PlatformBundle/Test/Unit/Controllers/AdminControllerTest.php

/**
 * Launch test with the command php vendor\phpunit\phpunit\phpunit
 * which reads the phpunit.xml.dist file
 */

namespace A2C\PlatformBundle\Tests\Unit\Controller;

use A2C\PlatformBundle\Entity\User;
use A2C\PlatformBundle\Entity\Advert;
use A2C\PlatformBundle\Entity\BannedAddress;
use A2C\PlatformBundle\Form\AdvertType;
use A2C\PlatformBundle\DataFixtures\ORM\LoadAdvert;
use A2C\PlatformBundle\DataFixtures\ORM\LoadBannedAddress;
use A2C\PlatformBundle\DataFixtures\ORM\LoadUser;

use A2C\PlatformBundle\Controller\AdminController;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\StringInput;

/**
 * Test of the AdminController class
 *
 * Assumes that all written fixtures are loaded.
 *
 * @author Vincent
 */
final class AdminControllerTest extends WebTestCase
{

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;
    protected static $application;

    public function setUp()
    {
        parent::setUp();
        static::$kernel = static::createKernel();
        static::$kernel->boot();
        $this->em = static::$kernel->getContainer()
                ->get('doctrine')
                ->getManager();

        self::runCommand('doctrine:fixtures:load -n');
    }

    protected static function runCommand($command)
    {
        $command = sprintf('%s --quiet', $command);

        return self::getApplication()->run(new StringInput($command));
    }

    protected static function getApplication()
    {
        if (null === self::$application) {
            $client = static::createClient();

            self::$application = new Application($client->getKernel());
            self::$application->setAutoExit(false);
        }

        return self::$application;
    }

    /**
     * Action : list
     * Tests if a wrong page number throws an exception.
     */
    public function testWrongPageNumber()
    {
        $client = static::createClient();

        // Page number < 1
        $client->request('GET', '/manage/admin/list/0');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());

        // Page number > max page number
        $client->request('GET', '/manage/admin/list/65535');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    /**
     * Action : list
     * Tests if there is the right number of advert on one page.
     */
    public function testRightAdvertNumber()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/manage/admin/list/1');
        $this->assertEquals(AdminController::NB_PER_PAGE, $crawler->filter('form')->count());
    }

    /**
     * Action : delete
     * Tests if an advert is effectively deleted.
     */
    public function testDeleteAdvert()
    {
        // Delete a random advert
        $client = static::createClient();
        $manager = $client->getContainer()->get('doctrine')->getManager();
        $advertsBefore = $manager->getRepository('A2CPlatformBundle:Advert')->findAll();
        $advertToDelete = $advertsBefore[count($advertsBefore)-1];
        //echo $advertToSave->getId();
        
        $crawler = $client->request('POST', "/manage/admin/list/1");

        // Generate the form
        $form = $crawler->filter('input[type=submit]')->first()->form();
        //$form = $crawler->selectButton('delete_')->form();
        //$form = $client->getContainer()->get('form.factory')->create();
        $client->submit($form);

        // Check the advert doesn't exists anymore and advert's number has been decreased
        $advertsAfter = $manager->getRepository('A2CPlatformBundle:Advert')->findAll();
        $this->assertEquals(count($advertsBefore) - 1, count($advertsAfter));

        $advert = $manager->getRepository('A2CPlatformBundle:Advert')->find($advertToDelete->getId());
        $this->assertEquals(NULL, $advert);
        
        // Re-create the deleted advert
        //TODO
        //$manager->persist($advertToDelete);
        //$manager->flush();
    }

    /**
     * Action : delete
     * Tests if delete an unexisted advert is throwing a error.
     */
    public function testDeleteWrongAdvert()
    {
        // Delete a random advert
        $advertsBefore = $this->em->getRepository('A2CPlatformBundle:Advert')->findAll();
        $maxId = $advertsBefore[count($advertsBefore)-1]->getId();
        $advertFakeId = rand($maxId, $maxId + 1000);
        $client = static::createClient();
        
        // Use a real URL to fetch the form
        $crawler = $client->request('POST', "/manage/admin/list/1");
        $form = $crawler->filter('input[type=submit]')->first()->form();
        
        // Use a fake URL
        $client->request('POST', "/manage/admin/list/$advertFakeId");
        $client->submit($form);
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }

    /**
     * Action : listBannedEmailAddress
     * Tests if a banned address appears in the list
     */
    public function testBanAddress()
    {
        $client = static::createClient();
        $manager = $client->getContainer()->get('doctrine')->getManager();
        $totalBefore = $manager->getRepository('A2CPlatformBundle:BannedAddress')->count(array());
        // Create the address
        $ba = new BannedAddress();
        $ba->setEmailAddress("test@ban.com");

        // Ban the address
        $manager->persist($ba);
        $manager->flush();
        
        // Check if the address is in the list
        $crawler = $client->request('GET', "/manage/admin/banned");
        $addressList = $crawler->filter('td')->each(function($node) {
            return $node->text();
        });
        $this->assertTrue(in_array($ba->getEmailAddress(), $addressList));
           
        // Check if the total has been incremented
        $totalAfter = $manager->getRepository('A2CPlatformBundle:BannedAddress')->count(array());
        $this->assertEquals($totalBefore+1, $totalAfter);
        
        // Unban the address
        $manager->remove($ba);
        $manager->flush();
    }

    /**
     * Action : listBannedEmailAddress
     * Tests if a not banned address appears in the list
     */
    public function testNotBannedAddress()
    {
        $client = static::createClient();
        $manager = $client->getContainer()->get('doctrine')->getManager();
        $users = $manager->getRepository('A2CPlatformBundle:User')->findAll();
        // Choose an address
        $user = $users[rand(0, count($users) - 1)];
        
        // Check if the address is the list
        $crawler = $client->request('GET', "/manage/admin/banned");
        $addressList = $crawler->filter('td')->each(function($node) {
            return $node->text();
        });
        $this->assertFalse(in_array($user->getEmailAddress(), $addressList));
    }
    
    /**
     * Action : unbanEmailAddress
     * Tests if unban an address effectively make it disappear from the list
     */
    public function testUnbanAddress()
    {
        $client = static::createClient();
        $manager = $client->getContainer()->get('doctrine')->getManager();
       
        // Choose an address
        $addressesBefore = $manager->getRepository('A2CPlatformBundle:BannedAddress')->findAll();
        $address = $addressesBefore[rand(0, count($addressesBefore)-1)];
        $client->request('GET', "/manage/admin/unban/".($address->getId()));
        
        // Check if the address is not anymore in the list
        $crawler = $client->request('GET', "/manage/admin/banned");
        $addressList = $crawler->filter('td')->each(function($node) {
            return $node->text();
        });
        $this->assertFalse(in_array($address->getEmailAddress(), $addressList));
        
        // Check if the address count has beeen decremented
        $addressesAfter = $manager->getRepository('A2CPlatformBundle:BannedAddress')->count(array());
        $this->assertEquals(count($addressesBefore) - 1, $addressesAfter);
        
        // Re-ban the address
        $manager->persist($address);
        $manager->flush();
    }
    
    /**
     * Action : unbanEmailAddress
     * Tests if unban an unexisted address produce an error and the total count is unchanged
     */
    public function testUnbanWrongAddress()
    {
        $client = static::createClient();
        $manager = $client->getContainer()->get('doctrine')->getManager();
        
        $addressesBefore = $manager->getRepository('A2CPlatformBundle:BannedAddress')->findAll();
        $maxId = $addressesBefore[count($addressesBefore)-1]->getId();
        $addressFakeId = rand($maxId, $maxId + 1000);

        // Request the unban of an unbanned address     
        $client->request('GET', "/manage/admin/unban/".$addressFakeId);
        
        // Check if we get an error
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
        
        // Check if the total is unchanged
        $addressesAfter = $manager->getRepository('A2CPlatformBundle:BannedAddress')->count(array());
        $this->assertEquals(count($addressesBefore), $addressesAfter);
    }

}
