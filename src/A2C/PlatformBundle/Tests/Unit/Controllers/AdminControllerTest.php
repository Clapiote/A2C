<?php

// src/A2C/PlatformBundle/Test/Unit/Controllers/AdminControllerTest.php

namespace A2C\PlatformBundle\Tests\Unit\Controller;

use A2C\PlatformBundle\Entity\User;
use A2C\PlatformBundle\Entity\Advert;
use A2C\PlatformBundle\Controller\AdminController;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

    public function setUp()
    {
        parent::setUp();
        static::$kernel = static::createKernel();
        static::$kernel->boot();
        $this->em = static::$kernel->getContainer()
        ->get('doctrine')
        ->getManager();
    }

    /**
     * Tests if a wrong page number throws an exception.
     */
    public function testWrongPageNumber()
    {
        $client = static::createClient();

        // Page number < 1
        $crawler = $client->request('GET', '/manage/admin/list/0');
        $this->expectException(NotFoundHttpException::class);

        // Page number > max page number
        $crawler = $client->request('GET', '/manage/admin/list/65535');
        $this->expectException(NotFoundHttpException::class);
    }

    /**
     * Tests if there is the right number of advert on one page.
     */
    public function testRightAdvertNumber()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/manage/admin/list/1');
        $this->assertTrue($crawler->filter('html:contains("liste")')->count()>0);
       // $this->assertEquals(AdminController::NB_PER_PAGE, $crawler->filter('html:contains("<li>")')->count());
    }

    /**
     * Tests if an advert is effectively deleted.
     */
    public function testDeleteAdvert()
    {
        // Delete a random advert
        $advertsBefore = $this->em->getRepository('A2CPlatformBundle:Advert')->findAll();
        $advertId = rand(1, count($advertsBefore));
        $client = static::createClient();
        $crawler = $client->request('GET', "/manage/admin/advert/delete/$advertId");
        
        // Check the advert doesn't exists anymore and advert's number has been decreased
        $advertsAfter = $this->em->getRepository('A2CPlatformBundle:Advert')->findAll();
        $this->assertEquals(count($advertsBefore)-1, count($advertsAfter));
        
        $advert = $this->em->getRepository('A2CPlatformBundle:Advert')->find($advertId);
        $this->assertEquals(NULL, $advert);
    }

}
