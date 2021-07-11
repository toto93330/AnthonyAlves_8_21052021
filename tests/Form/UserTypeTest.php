<?php

namespace App\Tests\Form;

use App\Form\UserType;
use App\Entity\User;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\Form;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class UserTypeTest
 * @package Tests\App\Form
 */
class UserTypeTest extends TypeTestCase
{
    private $validator;

    /**
     * @return array
     */
    protected function getExtensions()
    {
        $this->validator = $this->createMock(ValidatorInterface::class);
        $this->validator
            ->method('validate')
            ->will($this->returnValue(new ConstraintViolationList()));
        $this->validator
            ->method('getMetadataFor')
            ->will($this->returnValue(new ClassMetadata(Form::class)));

        return array(
            new ValidatorExtension($this->validator),
        );
    }

    public function testSubmitValidData()
    {
        $user = new User;

        $formData = array(
            'username' => 'John',
            'password' => array(
                'first' => 'root',
                'second' => 'root',
            ),
            'email' => 'root@root.com',
            'roles' => array('ROLE_USER'),
        );

        $form = $this->factory->create(UserType::class, $user);
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($user->getUsername(), $form->get('username')->getData());
        $this->assertEquals($user->getPassword(), $form->get('password')->getData());
        $this->assertEquals($user->getRoles(), $form->get('roles')->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
