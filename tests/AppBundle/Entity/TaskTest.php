<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use PHPUnit\Framework\TestCase;

/**
 * Class TaskTest
 * @package Tests\AppBundle\Entity
 */
class TaskTest extends TestCase
{

    /**
     * @var Task
     */
    private $task;

    /**
     * @var dateTime
     */
    private $date;

    public function setUp()
    {
        $this->task = new Task();
        $this->date = new \DateTime();
    }

    public function testCreatedAt()
    {
        $this->task->setCreatedAt($this->date);
        $this->assertSame($this->date, $this->task->getCreatedAt());
    }

    public function testId()
    {
        $this->assertNull($this->task->getId());
    }

    public function testTitle()
    {
        $this->task->setTitle('Test du titre');
        $this->assertSame($this->task->getTitle(), 'Test du titre');
    }

    public function testContent()
    {
        $this->task->setContent('Test du contenu');
        $this->assertSame($this->task->getContent(), 'Test du contenu');
    }

    public function testIsDone()
    {
        $this->task->toggle(true);
        $this->assertEquals($this->task->isDone(), true);
    }

    public function testUser()
    {
        $this->task->setUser(new User());
        $this->assertInstanceOf(User::class, $this->task->getUser());
    }
}
