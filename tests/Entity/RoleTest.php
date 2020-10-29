<?php


namespace App\Tests\Entity;


use App\Entity\Role;
use App\Entity\Task;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class RoleTest extends KernelTestCase
{
    public function testValidEntityRole()
    {
        self::bootKernel();

        $user = new User();
        $user->setPassword('123456')
            ->setUsername('loganTest')
            ->setEmail("email@test.com");

        $role = new Role();
        $role->setTitle('ROLE_TEST');
        $role->addUser($user);

        $this->assertSame('ROLE_TEST', $role->getTitle());
        $this->assertSame('loganTest', $role->getUsers()[0]->getUsername());

//        var_dump($role->getUsers()[0]->getUsername());


        $error = self::$container->get('validator')->validate($role);

        $this->assertCount(0, $error);

    }

//    public function testNonValidEntity()
//    {
//        self::bootKernel();
//
//        $task = new Task();
//        $task->setTitle('titre de la task');
//        $task->setContent('');
//        $task->setAuthor(null);
//
//
//
//        $error = self::$container->get('validator')->validate($task);
//
//        $this->assertCount(1, $error);
//
//    }


    public function testDoneCreatedRole()
    {
        $role = new Role();

        $this->assertNull($role->getId());

    }

}