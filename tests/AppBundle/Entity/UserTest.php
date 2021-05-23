<?php

namespace App\Tests\Entity;

use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    /**
     * test unitaire de la class User
     */
    public function testUser()
    {
        $user = new User();
        $task = new Task();
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
