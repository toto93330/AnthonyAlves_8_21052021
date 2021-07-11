<?php

namespace App\Tests\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class DefaultControllerTest
 * @package App\Tests\Controller
 */
class DefaultControllerTest extends WebTestCase
{

    public function testIndexAction()
    {
        $client = static::createClient();
        $client->request('GET', '/');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains("h1", "Bienvenue sur Todo List, l'application vous permettant de gérer l'ensemble de vos tâches sans effort !");
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form([
            'email' => 'user@user.fr',
            'password' => 'root'
        ]);
        $this->assertSelectorNotExists('.alert.alert-danger');
        $client->submit($form);
        $this->assertResponseRedirects('/');
        $client->followRedirect();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testHomepageLoginWithAdmin()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form([
            'email' => 'root@root.fr',
            'password' => 'root'
        ]);
        $client->submit($form);
        $this->assertResponseRedirects();
        $client->followRedirect();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains("h1", "Bienvenue sur Todo List, l'application vous permettant de gérer l'ensemble de vos tâches sans effort !");
    }



    public function testLoginWithInvalidData()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form([
            'email' => 'xxxx@xxxx.fr',
            'password' => 'xxxxx'
        ]);
        $client->submit($form);
        $this->assertResponseRedirects();
        $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-danger');
    }

    public function testLogoutCheck()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form();
        $client->submit($form, array('email' => 'user@user.fr', 'password' => 'root'));
        $client->request('GET', '/logout');
        return $this->assertSame(302, $client->getResponse()->getStatusCode());
    }
}
