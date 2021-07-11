<?php

namespace App\Tests\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class TaskControllerTest
 * @package App\Tests\Controller
 */
class TaskControllerTest extends WebTestCase
{


    public function testListTask()
    {
        $client = static::createClient();
        $client->request('GET', '/tasks');
        self::assertEquals(302, $client->getResponse()->getStatusCode());
        $client->followRedirect();
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form([
            'email' => 'root@root.fr',
            'password' => 'root'
        ]);
        $this->assertSelectorNotExists('.alert.alert-danger');
        $client->submit($form);

        $client->followRedirect();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $crawler = $client->request('GET', '/tasks');
        self::assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testCreateTask()
    {
        $client = static::createClient();
        $client->request('GET', '/tasks/create');
        self::assertEquals(302, $client->getResponse()->getStatusCode());

        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form();
        $client->submit($form, array('email' => 'root@root.fr', 'password' => 'root'));
        $this->assertSame(302, $client->getResponse()->getStatusCode());

        $crawler = $client->request('GET', '/tasks/create');
        self::assertEquals(200, $client->getResponse()->getStatusCode());
        self::assertCount(2, $crawler->filter('input'));
        self::assertEquals('Ajouter', $crawler->filter('button.btn.btn-success')->text());

        $buttonCrawlerMode = $crawler->filter('form');
        $form = $buttonCrawlerMode->form([
            'task[title]' => 'Titre de la tâche 2',
            'task[content]' => 'Description de la tâche 2'
        ]);

        $client->submit($form);
        self::assertEquals(302, $client->getResponse()->getStatusCode());
    }

    public function testEditTask()
    {
        $client = static::createClient();
        $client->request('GET', '/tasks/1/edit');
        self::assertEquals(302, $client->getResponse()->getStatusCode());

        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form();
        $client->submit($form, array('email' => 'root@root.fr', 'password' => 'root'));
        $this->assertSame(302, $client->getResponse()->getStatusCode());

        $crawler = $client->request('GET', '/tasks/1/edit');
        self::assertEquals(200, $client->getResponse()->getStatusCode());
        self::assertCount(2, $crawler->filter('input'));
        self::assertEquals('Modifier', $crawler->filter('button.btn.btn-success')->text());

        $buttonCrawlerMode = $crawler->filter('form');
        $form = $buttonCrawlerMode->form([
            'task[title]' => 'Titre de la tâche',
            'task[content]' => 'Description de la tâche'
        ]);

        $client->submit($form);
        self::assertEquals(302, $client->getResponse()->getStatusCode());
    }

    public function testToggle()
    {
        $client = static::createClient();
        $client->request('GET', '/tasks/1/toggle');
        self::assertEquals(302, $client->getResponse()->getStatusCode());

        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form();
        $client->submit($form, array('email' => 'root@root.fr', 'password' => 'root'));
        $this->assertSame(302, $client->getResponse()->getStatusCode());

        $client->request('GET', '/tasks/1/toggle');
        self::assertEquals(302, $client->getResponse()->getStatusCode());
    }

    public function testDelete()
    {
        $client = static::createClient();
        $client->request('DELETE', '/tasks/19/delete');
        self::assertEquals(302, $client->getResponse()->getStatusCode());

        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form();
        $client->submit($form, array('email' => 'root@root.fr', 'password' => 'root'));
        $this->assertSame(302, $client->getResponse()->getStatusCode());

        $client->request('DELETE', '/tasks/19/delete');
        self::assertEquals(302, $client->getResponse()->getStatusCode());
    }

    public function testAccessDelete()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form();
        $client->submit($form, array('email' => 'user@user.fr', 'password' => 'root'));
        $this->assertSame(302, $client->getResponse()->getStatusCode());

        $client->request('DELETE', '/tasks/4/delete');
        self::assertEquals(403, $client->getResponse()->getStatusCode());
    }
}
