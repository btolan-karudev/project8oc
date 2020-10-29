<?php


namespace App\Tests\Entity;


use App\Entity\Role;
use App\Entity\Task;
use App\Entity\User;

use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\ConstraintViolation;

class UserTest extends KernelTestCase
{
    use FixturesTrait;

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

    public function testId()
    {
        $user = new User();
        $this->assertSame(null, $user->getId());
    }

    public function testUsername()
    {
        $user = new User();
        $user->setUsername('Test username');
        $this->assertSame('Test username', $user->getUsername());
    }

    public function testPassword()
    {
        $user = new User();
        $user->setPassword('Test password');
        $this->assertSame('Test password', $user->getPassword());
    }

    public function testEmail()
    {
        $user = new User();
        $user->setEmail('test@gmail.com');
        $this->assertSame('test@gmail.com', $user->getEmail());
    }

    public function testRole()
    {
        $userRole = new Role();
        $userRole->setTitle('ROLE_USER');

        $user = new User();
        $user->addUserRole($userRole);
        $this->assertContains('ROLE_USER', $user->getRoles()[0]);
    }

    public function testRoleRemove()
    {
        $userRole = new Role();
        $userRole->setTitle('ROLE_TEST');

//        $testmook = $this->getMockBuilder(RoleRepository::class)->getMock();

        $user = new User();
        $user->addUserRole($userRole);
        $this->assertContains('ROLE_TEST', $user->getRoles()[0]);
        $user->removeUserRole($userRole);
        $this->assertNotNull('ROLE_USER', $user->getUserRoles());
    }

    public function testTaskBelongsToUser()
    {
        $task1 = new Task();
        $task2 = new Task();

        $user = new User;
        $user->addTask($task1);
        $user->addTask($task2);

        $this->assertCount(2, $user->getTasks());

        $user->removeTask($task2);
        $this->assertCount(1, $user->getTasks());
    }

    public function assertHasErrors(User $user, int $number = 0)
    {
        self::bootKernel();
        $errors = self::$container->get('validator')->validate($user);
        $messages = [];

        /** @var ConstraintViolation $error */
        foreach ($errors as $error) {

            $messages[] = $error->getPropertyPath() . ' => ' . $error->getMessage();
        }
        $this->assertCount($number, $errors, implode(', ', $messages));
    }

}