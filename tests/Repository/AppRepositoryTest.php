<?php


namespace App\Tests\Repository;


use App\DataFixtures\AppFixtures;
use App\Repository\RoleRepository;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AppRepositoryTest extends KernelTestCase
{
    use FixturesTrait;

    public function testCount()
    {
        self::bootKernel();
        $this->loadFixtures([AppFixtures::class]);
        $users = self::$container->get(UserRepository::class)->count([]);
        $tasks = self::$container->get(TaskRepository::class)->count([]);
        $roles = self::$container->get(RoleRepository::class)->count([]);

        $this->assertEquals(12, $users);
        $this->assertEquals(100, $tasks);
        $this->assertEquals(1, $roles);
    }

}