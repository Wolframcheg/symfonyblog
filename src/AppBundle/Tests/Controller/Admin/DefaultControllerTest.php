<?php

namespace AppBundle\Tests\Controller\Admin;

use AppBundle\Tests\Controller\BaseControllerTest;


class DefaultControllerTest extends BaseControllerTest
{
    public function testIndex()
    {
        $this->logIn();
        $client = $this->client;

        $crawler = $client->request('GET', '/admin');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(
            1,
            $crawler->filter('h1')->count()
        );
    }


//    public function testShow()
//    {
//        $client = static::createClient();
//
//        $em = $client->getContainer()->get('doctrine.orm.entity_manager');
//        $slug = $em
//            ->getRepository('AppBundle:Country')
//            ->findOneBy([])->getSlug();
//
//        $crawler = $client->request('GET', "/country/{$slug}");
//        $this->assertEquals(200, $client->getResponse()->getStatusCode());
//        $this->assertEquals(
//            1,
//            $crawler->filter('h1')->count()
//        );
//    }

}