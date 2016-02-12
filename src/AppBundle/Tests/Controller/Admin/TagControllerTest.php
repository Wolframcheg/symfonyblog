<?php

namespace AppBundle\Tests\Controller\Admin;

use AppBundle\Tests\Controller\BaseTestController;


class TagControllerTest extends BaseTestController
{
    public function testIndex()
    {
        $this->logIn();
        $client = $this->client;
        $crawler = $client->request('GET', '/admin/tag');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(
            1,
            $crawler->filter('h1')->count()
        );
    }


    public function testNew()
    {
        $this->logIn();
        $client = $this->client;
        $crawler = $client->request('GET', '/admin/tag/new');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(
            1,
            $crawler->filter('h1')->count()
        );
    }

    public function testEdit()
    {
        $this->logIn();
        $client = $this->client;

        $em = $client->getContainer()->get('doctrine.orm.entity_manager');
        $id = $em
            ->getRepository('AppBundle:Tag')
            ->findOneBy([])->getId();

        $crawler = $client->request('GET', "/admin/tag/{$id}/edit");

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(
            1,
            $crawler->filter('h1')->count()
        );
    }

}