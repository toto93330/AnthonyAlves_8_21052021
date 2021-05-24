<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;
use phpDocumentor\Reflection\Types\This;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class UserControllerTest
 * @package Tests\AppBundle\Controller
 */
class UserControllerTest extends WebTestCase
{

    private $client;

    public function setUp()
    {
        $this->client = $this->createClient();
    }

    public function testUserList()
    {
        $this->client->request('GET', '/users');
        self::assertEquals(302, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form();
        $this->client->submit($form, array('_username' => 'root', '_password' => 'root'));
        $this->assertSame(302, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->request('GET', '/users');
        self::assertEquals(200, $this->client->getResponse()->getStatusCode());
        self::assertContains('Liste des utilisateurs', $crawler->filter('h1')->text());
        self::assertContains('Edit', $crawler->filter('a.btn.btn-success')->text());
    }

    public function testCreateUser()
    {
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form();
        $this->client->submit($form, array('_username' => 'root', '_password' => 'root'));
        $this->assertSame(302, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->request('GET', '/users/create');
        self::assertEquals(200, $this->client->getResponse()->getStatusCode());
        self::assertContains('Créer un utilisateur', $crawler->filter('h1')->text());
        self::assertContains('Ajouter', $crawler->filter('button.btn.btn-success')->text());
        self::assertCount(7, $crawler->filter('input'));

        $form = $crawler->selectButton('Ajouter')->form();
        $this->client->submit($form, [
            'user[username]' => 'John1',
            'user[password][first]' => 'root',
            'user[password][second]' => 'root',
            'user[email]' => 'john@doe.fr',
            'user[roles][0]' => 'ROLE_USER'
        ]);

        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
    }

    public function testEditUser()
    {
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form();
        $this->client->submit($form, array('_username' => 'root', '_password' => 'root'));
        $this->assertSame(302, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->request('GET', '/users/1/edit');
        self::assertEquals(200, $this->client->getResponse()->getStatusCode());
        self::assertContains('Modifier', $crawler->filter('button.btn.btn-success')->text());
        self::assertCount(7, $crawler->filter('input'));

        $form = $crawler->selectButton('Modifier')->form();
        $this->client->submit($form, [
            'user[username]' => 'John',
            'user[password][first]' => 'root',
            'user[password][second]' => 'root',
            'user[email]' => 'john@doe.fr',
            'user[roles][0]' => 'ROLE_USER',
            'user[roles][1]' => 'ROLE_ADMIN',
        ]);

        self::assertEquals(302, $this->client->getResponse()->getStatusCode());
    }

    public function testAccessListWithUser()
    {
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form();
        $this->client->submit($form, array('_username' => 'test', '_password' => 'test'));
        $this->assertSame(302, $this->client->getResponse()->getStatusCode());

        $this->client->request('GET', '/users');
        self::assertEquals(403, $this->client->getResponse()->getStatusCode());
    }
}
