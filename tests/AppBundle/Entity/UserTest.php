<?php

/**
 * @author Sébastien Rochat <percevalseb@gmail.com>
 */

namespace Tests\AppBundle\Controller;

use Symfony\Component\HTTPFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class UserControllerTest
 * @package Tests\AppBundle\Controller
 */
class UserControllerTest extends WebTestCase
{
    /**
     * @var null
     */
    private $client = null;

    /**
     * @var string
     */
    private $usernameCreated = 'root666';

    /**
     * @var string
     */
    private $usernameUpdated = 'root666';

    protected function setUp()
    {
        $this->client = $this->createClient();
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form();
        $this->client->submit($form, array('_username' => 'root', '_password' => 'root'));
    }

    public function testListAction()
    {
        $crawler = $this->client->request('GET', '/users');
        static::assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        static::assertContains('Liste des utilisateurs', $crawler->filter('h1')->text());
    }

    public function testCreateAction()
    {
        $crawler = $this->client->request('GET', '/users');
        $link = $crawler->selectLink('Créer un utilisateur')->link();
        $crawler = $this->client->click($link);
        static::assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        static::assertContains('Créer un utilisateur', $crawler->filter('h1')->text());
        static::assertContains('/users/create', $crawler->filter('form')->attr('action'));
    }

    public function testCreateActionWithValidData()
    {
        $crawler = $this->client->request('GET', '/users/create');
        static::assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Ajouter')->form();
        $form['user[username]'] = $this->usernameCreated;
        $form['user[password][first]'] = 'root';
        $form['user[password][second]'] = 'root';
        $form['user[email]'] = 'test2@test2.com';
        $form['user[roles][0]']->tick();
        $this->client->submit($form);
        static::assertSame(Response::HTTP_FOUND, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->followRedirect();
        static::assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        static::assertContains("L'utilisateur a bien été ajouté.", $crawler->filter('div.alert-success')->text());
        static::assertSame(1, $crawler->filter('td:contains("' . $this->usernameCreated . '")')->count());
    }

    public function testCreateActionWithInvalidData()
    {
        $crawler = $this->client->request('GET', '/users/create');

        $form = $crawler->selectButton('Ajouter')->form();
        $form['user[username]'] = $this->usernameCreated;
        $form['user[password][first]'] = 'root';
        $form['user[password][second]'] = 'root1';
        $form['user[email]'] = 'contacttoto.com';
        $crawler = $this->client->submit($form);
        static::assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        static::assertSame(1, $crawler->filter('span.help-block:contains("Ce nom d\'utilisateur existe déjà.")')->count());
        static::assertSame(1, $crawler->filter('span.help-block:contains("Les deux mots de passe doivent correspondre.")')->count());
        static::assertSame(1, $crawler->filter('span.help-block:contains("Le format de l\'adresse email n\'est pas correcte.")')->count());
        static::assertSame(1, $crawler->filter('span.help-block:contains("Vous devez cocher au moins un rôle.")')->count());
    }

    public function testEditAction()
    {
        $crawler = $this->client->request('GET', '/users');
        $link = $crawler->selectLink('Edit')->last()->link();
        $crawler = $this->client->click($link);
        static::assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        static::assertContains('Modifier', $crawler->filter('h1')->text());

        $form = $crawler->selectButton('Modifier')->form();
        $form['user[username]'] = $this->usernameUpdated;
        $form['user[password][first]'] = 'root';
        $form['user[password][second]'] = 'root';
        $this->client->submit($form);
        static::assertSame(Response::HTTP_FOUND, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->followRedirect();
        static::assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        static::assertContains("L'utilisateur a bien été modifié.", $crawler->filter('div.alert-success')->text());
        static::assertSame(1, $crawler->filter('td:contains("' . $this->usernameUpdated . '")')->count());
    }

    public function testRoles()
    {
        $roles = array("ROLE_USER");
        $this->user->setRoles($roles);
    }

    public function testEraseCredential()
    {
        $this->assertNull($this->user->eraseCredentials());
    }
}
