<?php

namespace App\Tests\Entity;

use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{


    private $user;
    private $task;


    public function setUp()
    {
        $this->task = new Task();
        $this->user = new User();
    }


    public function testUser()
    {
        $this->assertNull($this->user->getId());

        $this->user->setUsername('test');

        $this->assertSame('test', $this->user->getUsername());

        $this->user->setPassword('test');

        $this->assertSame('test', $this->user->getPassword());

        $this->user->setEmail('test@symfony.com');

        $this->assertSame('test@symfony.com', $this->user->getEmail());

        $this->user->setRoles(['ROLE_USER']);

        $this->assertSame(['ROLE_USER'], $this->user->getRoles());

        $this->task->setContent('test content');

        $this->task->setTitle('test titre');

        $this->task->setUser($this->user);

        $this->assertSame($this->task->getUser(), $this->user);

        $this->assertNotEmpty($this->task->getUser());

        $this->assertNull($this->user->getSalt());
    }
}
