<?php


namespace App\Tests\Repository;


use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AppRepositoryTest extends KernelTestCase
{
    public function  testCount() {
        $kernel = self::bootKernel();
        $kernel->getContainer()->get(UserRepository::class)
    }

}