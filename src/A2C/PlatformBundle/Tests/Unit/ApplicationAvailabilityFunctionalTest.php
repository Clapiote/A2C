<?php

// src/A2C/PlatformBundle/Test/Unit/ApplicationAvailabilityFunctionalTest.php

namespace A2C\PlatformBundle\Tests\Unit;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApplicationAvailabilityFunctionalTest extends WebTestCase
{

    /**
     * @dataProvider urlProvider
     */
    public function testPageIsSuccessful($url)
    {
        $client = self::createClient();
        $client->request('GET', $url);

        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    public function urlProvider()
    {
        return array(
            array('/'),
            array('/home'),
            //array('/advert/view'),
            //array('/advert/create'),
            //array('/advert/delete'),
            array('/contact'),
            array('/manage/admin'),
            array('/manage/admin/list'),
            //array('/manage/admin/banned'),
            //array('/manage/admin/mail'),
        );
    }

}
