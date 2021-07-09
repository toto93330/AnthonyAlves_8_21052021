<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class TaskControllerTest
 * @package Tests\AppBundle\Controller
 */
class TaskControllerTest extends WebTestCase
{

    private $client;

    public function setUp()
    {
        $this->client = $this->createClient();
    }

    public function testListTask()
    {
        $this->client->request('GET', '/tasks');
        self::assertEquals(302, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form();
        $this->client->submit($form, array('_username' => 'root', '_password' => 'root'));
        $this->assertSame(302, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->request('GET', '/tasks');
        self::assertEquals(200, $this->client->getResponse()->getStatusCode());
        self::assertContains('Créer une tâche', $crawler->filter('a.btn.btn-info')->text());
    }

    public function testCreateTask()
    {
        $this->client->request('GET', '/tasks/create');
        self::assertEquals(302, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form();
        $this->client->submit($form, array('_username' => 'root', '_password' => 'root'));
        $this->assertSame(302, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->request('GET', '/tasks/create');
        self::assertEquals(200, $this->client->getResponse()->getStatusCode());
        self::assertCount(2, $crawler->filter('input'));
        self::assertEquals('Ajouter', $crawler->filter('button.btn.btn-success')->text());

        $buttonCrawlerMode = $crawler->filter('form');
        $form = $buttonCrawlerMode->form([
            'task[title]' => 'Titre de la tâche 2',
            'task[content]' => 'Description de la tâche 2'
        ]);

        $this->client->submit($form);
        self::assertEquals(302, $this->client->getResponse()->getStatusCode());
    }

    public function testEditTask()
    {
        $this->client->request('GET', '/tasks/1/edit');
        self::assertEquals(302, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form();
        $this->client->submit($form, array('_username' => 'root', '_password' => 'root'));
        $this->assertSame(302, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->request('GET', '/tasks/1/edit');
        self::assertEquals(200, $this->client->getResponse()->getStatusCode());
        self::assertCount(2, $crawler->filter('input'));
        self::assertEquals('Modifier', $crawler->filter('button.btn.btn-success')->text());

        $buttonCrawlerMode = $crawler->filter('form');
        $form = $buttonCrawlerMode->form([
            'task[title]' => 'Titre de la tâche',
            'task[content]' => 'Description de la tâche'
        ]);

        $this->client->submit($form);
        self::assertEquals(302, $this->client->getResponse()->getStatusCode());
    }

    public function testToggle()
    {
        $this->client->request('GET', '/tasks/1/toggle');
        self::assertEquals(302, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form();
        $this->client->submit($form, array('_username' => 'root', '_password' => 'root'));
        $this->assertSame(302, $this->client->getResponse()->getStatusCode());

        $this->client->request('GET', '/tasks/1/toggle');
        self::assertEquals(302, $this->client->getResponse()->getStatusCode());
    }

    public function testDelete()
    {
        $this->client->request('DELETE', '/tasks/1/delete');
        self::assertEquals(302, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form();
        $this->client->submit($form, array('_username' => 'root', '_password' => 'root'));
        $this->assertSame(302, $this->client->getResponse()->getStatusCode());

        $this->client->request('DELETE', '/tasks/1/delete');
        self::assertEquals(302, $this->client->getResponse()->getStatusCode());
    }

    public function testAccessDelete()
    {
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form();
        $this->client->submit($form, array('_username' => 'test', '_password' => 'test'));
        $this->assertSame(302, $this->client->getResponse()->getStatusCode());

        $this->client->request('DELETE', '/tasks/1/delete');
        self::assertEquals(404, $this->client->getResponse()->getStatusCode());
    }
}
