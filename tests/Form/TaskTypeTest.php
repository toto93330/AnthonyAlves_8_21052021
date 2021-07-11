<?php

namespace App\Tests\Form;

use App\Form\TaskType;
use App\Entity\Task;
use Symfony\Component\Form\Test\TypeTestCase;

/**
 * Class TaskTypeTest
 * @package Tests\AppBundle\Form
 */
class TaskTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $task = new Task;

        $formData = array(
            'title' => 'Tâche de test',
            'content' => 'Finir le projet 8 openclassrooms.',
        );

        $form = $this->factory->create(TaskType::class, $task);
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($task->getTitle(), $form->get('title')->getData());
        $this->assertEquals($task->getContent(), $form->get('content')->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
