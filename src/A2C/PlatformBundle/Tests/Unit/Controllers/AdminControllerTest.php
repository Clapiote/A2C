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
        
    public function setUp() 
    {
        parent::setUp();
    }
	
	/*
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
	
	/*
	* Tests if there is the right number of advert on one page.
	*/
	public function testRightAdvertNumber()
	{
		$client = static::createClient();
		$crawler = $client->request('GET', '/manage/admin/list/1');
		$this->assertEqual(AdminController::NB_PER_PAGE, $crawler->filter('html:contains("<li>")')->count());
	}
    

}
