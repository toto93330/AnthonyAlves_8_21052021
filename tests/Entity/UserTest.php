<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class UserTest
 * @package App\Tests\Entity
 */
class UserTest extends KernelTestCase
{


    public function testValidUser()
    {
        $code = (new User())
            ->setEmail('newuser@newuser.fr')
            ->setRoles(['ROLE_USER'])
            ->setPassword('root')
            ->setUsername("newuser");

        self::bootKernel();

        $error = self::$container->get('validator')->validate($code);

        $this->assertCount(0, $error);
    }

    public function testInvalidUser()
    {
        $code = (new User())
            ->setEmail('error@error.fr')
            ->setRoles(['ROLE_USER'])
            ->setPassword('root')
            ->setUsername("");


        self::bootKernel();

        $error = self::$container->get('validator')->validate($code);

        $this->assertCount(2, $error);
    }


    public function testUser()
    {

        $task = new Task();
        $user = new User();

        $this->assertNull($user->getId());

        $user->setUsername('test');

        $this->assertSame('test', $user->getUsername());

        $user->setPassword('test');

        $this->assertSame('test', $user->getPassword());

        $user->setEmail('test@symfony.com');

        $this->assertSame('test@symfony.com', $user->getEmail());

        $user->setRoles(['ROLE_USER']);

        $this->assertSame(['ROLE_USER'], $user->getRoles());

        $task->setContent('test content');

        $task->setTitle('test titre');

        $task->setUser($user);

        $this->assertSame($task->getUser(), $user);

        $this->assertNotEmpty($task->getUser());

        $this->assertNull($user->getSalt());
    }
}
