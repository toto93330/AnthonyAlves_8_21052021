<?php

namespace Tests\AppBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class DefaultControllerTest
 * @package Tests\AppBundle\Controller
 */
class DefaultControllerTest extends WebTestCase
{

    private $client;

    public function setUp()
    {
        $this->client = $this->createClient();
    }


    public function testIndexAction()
    {

        $crawler = $this->client->request('GET', '/');
        $this->assertSame(302, $this->client->getResponse()->getStatusCode());
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form();
        $this->client->submit($form, array('_username' => 'test', '_password' => 'test'));
        $this->assertSame(302, $this->client->getResponse()->getStatusCode());
    }

    public function testHomepageLoginWithAdmin()
    {
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form();
        $this->client->submit($form, array('_username' => 'root', '_password' => 'root'));
        $this->assertSame(302, $this->client->getResponse()->getStatusCode());
    }



    public function testLoginWithInvalidData()
    {
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form();
        $this->client->submit($form, array('_username' => 'xxx', '_password' => 'xxx'));
        static::assertSame(Response::HTTP_FOUND, $this->client->getResponse()->getStatusCode());
        $this->crawler = $this->client->followRedirect();
        static::assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        static::assertContains("Invalid credentials.", $this->crawler->filter('div.alert-danger')->text());
    }

    public function testLogoutCheck()
    {
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form();
        $this->client->submit($form, array('_username' => 'test', '_password' => 'test'));
        $this->client->request('GET', '/logout');
        return $this->assertSame(302, $this->client->getResponse()->getStatusCode());
    }
}
