<?

namespace Tests\AppBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class UserControllerTest
 * @package Tests\AppBundle\Controller
 */
class UserControllerTest extends WebTestCase
{

    public function testIndexAction()
    {
        $this->client = $this->createClient();
        $crawler = $this->client->request('GET', '/');
        $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form();
        $this->client->submit($form, array('_username' => 'test', '_password' => 'test'));
        $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    public function testHomepageWithLoggedAdmin()
    {
        $this->client = $this->createClient();
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form();
        $this->client->submit($form, array('_username' => 'test', '_password' => 'test'));
        $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }
}
