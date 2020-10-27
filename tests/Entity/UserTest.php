<?php


namespace App\Tests\Entity;


use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserTest extends KernelTestCase
{
    public function testValidEntity()
    {
        self::bootKernel();

        $user = new User();
        $user->setPassword('123456')
            ->setUsername('logan')
            ->setEmail("email@test.com");

        $error = self::$container->get('validator')->validate($user);

        $this->assertCount(0, $error);

    }

    public function testNonValidEntity()
    {
        self::bootKernel();

        $user = new User();
        $user->setPassword('123456')
            ->setUsername('logan')
            ->setEmail('');

        $error = self::$container->get('validator')->validate($user);

        $this->assertCount(1, $error);

    }

}