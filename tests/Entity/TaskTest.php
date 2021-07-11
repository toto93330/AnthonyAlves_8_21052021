<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use App\Entity\User;
use DateTime;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class TaskTest
 * @package App\Tests\Entity
 */
class TaskTest extends KernelTestCase
{



    public function testValidTask()
    {
        $code = (new Task())
            ->setTitle("titre de test")
            ->setUser(new User())
            ->setCreatedAt(new DateTime('Now'))
            ->setIsDone(0)
            ->setContent('blablablabla');

        self::bootKernel();

        $error = self::$container->get('validator')->validate($code);

        $this->assertCount(0, $error);
    }

    public function testInvalidTask()
    {
        $code = (new Task())
            ->setTitle("")
            ->setUser(new User())
            ->setCreatedAt(new DateTime('Now'))
            ->setIsDone(0)
            ->setContent('blablablabla');

        self::bootKernel();

        $error = self::$container->get('validator')->validate($code);

        $this->assertCount(1, $error);
    }


    public function testCreatedAt()
    {
        $task = new Task();
        $date = new DateTime('Now');
        $task->setCreatedAt($date);
        $this->assertSame($date, $task->getCreatedAt());
    }

    public function testId()
    {
        $task = new Task();
        $this->assertNull($task->getId());
    }

    public function testTitle()
    {
        $task = new Task();
        $task->setTitle('Test du titre');
        $this->assertSame($task->getTitle(), 'Test du titre');
    }

    public function testContent()
    {
        $task = new Task();
        $task->setContent('Test du contenu');
        $this->assertSame($task->getContent(), 'Test du contenu');
    }

    public function testIsDone()
    {
        $task = new Task();
        $task->toggle(true);
        $this->assertEquals($task->isDone(), true);
    }

    public function testUser()
    {
        $task = new Task();
        $task->setUser(new User());
        $this->assertInstanceOf(User::class, $task->getUser());
    }
}
