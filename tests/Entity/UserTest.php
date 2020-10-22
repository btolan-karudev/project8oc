<?php


namespace App\Tests\Entity;


use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserTest extends KernelTestCase
{
    public function testValidEntity()
    {
        $user = (new User())
            ->setPassword('123456')
            ->setUsername('logan')
            ->setEmail("email@test.com");
        self::bootKernel();
        $error = self::$container->get('validator')->validate($user);
        $this->assertCount(0, $error);


    }

}