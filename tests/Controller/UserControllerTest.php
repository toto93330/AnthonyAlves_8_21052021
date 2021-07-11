<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class UserControllerTest
 * @package App\Tests\Controller
 */
class UserControllerTest extends WebTestCase
{

    public function testUserList()
    {
        $client = static::createClient();
        $client->request('GET', '/users');
        self::assertEquals(302, $client->getResponse()->getStatusCode());

        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form();
        $client->submit($form, array('email' => 'root@root.fr', 'password' => 'root'));
        $this->assertSame(302, $client->getResponse()->getStatusCode());

        $crawler = $client->request('GET', '/users');
        self::assertEquals(200, $client->getResponse()->getStatusCode());
        self::assertEquals('Liste des utilisateurs', $crawler->filter('h1')->text());
        self::assertEquals('Edit', $crawler->filter('a.btn.btn-success')->text());
    }

    public function testCreateUser()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form();
        $client->submit($form, array('email' => 'root@root.fr', 'password' => 'root'));
        $this->assertSame(302, $client->getResponse()->getStatusCode());

        $crawler = $client->request('GET', '/users/create');
        self::assertEquals(200, $client->getResponse()->getStatusCode());
        self::assertEquals('Créer un utilisateur', $crawler->filter('h1')->text());
        self::assertEquals('Ajouter', $crawler->filter('button.btn.btn-success')->text());
        self::assertCount(7, $crawler->filter('input'));

        $form = $crawler->selectButton('Ajouter')->form();
        $client->submit($form, [
            'user[username]' => 'John1',
            'user[password][first]' => 'root',
            'user[password][second]' => 'root',
            'user[email]' => 'john@doe.fr',
            'user[roles][0]' => 'ROLE_USER'
        ]);

        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }

    public function testEditUser()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form();
        $client->submit($form, array('email' => 'root@root.fr', 'password' => 'root'));
        $this->assertSame(302, $client->getResponse()->getStatusCode());

        $crawler = $client->request('GET', '/users/2/edit');
        self::assertEquals(200, $client->getResponse()->getStatusCode());
        self::assertEquals('Modifier', $crawler->filter('button.btn.btn-success')->text());
        self::assertCount(7, $crawler->filter('input'));

        $form = $crawler->selectButton('Modifier')->form();
        $client->submit($form, [
            'user[username]' => 'John',
            'user[password][first]' => 'root',
            'user[password][second]' => 'root',
            'user[email]' => 'john@doe.fr',
            'user[roles][0]' => 'ROLE_USER',
            'user[roles][1]' => 'ROLE_ADMIN',
        ]);

        self::assertEquals(302, $client->getResponse()->getStatusCode());
    }

    public function testAccessListWithUser()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form();
        $client->submit($form, array('email' => 'user@user.fr', 'password' => 'root'));
        $this->assertSame(302, $client->getResponse()->getStatusCode());

        $client->request('GET', '/users');
        self::assertEquals(403, $client->getResponse()->getStatusCode());
    }
}
