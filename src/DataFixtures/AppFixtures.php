<?php

namespace App\DataFixtures;

use App\Entity\Role;
use App\Entity\Task;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $adminRole = new Role();
        $adminRole->setTitle('ROLE_ADMIN');
        $manager->persist($adminRole);

        $adminUser = new User();
        $adminUser->setUsername('beniamin');
        $adminUser->setPassword($this->encoder->encodePassword($adminUser, 'admin'));
        $adminUser->setEmail('beniamin@tolan.me');
        $adminUser->addUserRole($adminRole);

        $manager->persist($adminUser);

        //User management
        $users = [];
        for ($i = 1; $i <= 10; $i++) {
            $user = new User();

            $password = $this->encoder->encodePassword($user, "123456");
            $user->setUsername("user$i");
            $user->setEmail("user$i@gmail.com");
            $user->setPassword($password);

            $manager->persist($user);
            $users[] = $user;
        }

        // Task management
        for ($i = 1; $i <= 100; $i++) {
            $task = new Task();

            $user = $users[mt_rand(0, count($users) - 1)];

            $task->setTitle("Titre n°$i");
            $task->setCreatedAt(new \DateTime());
            $task->setContent("<p>Je suis le contendu de cette action</p>");
            $task->setAuthor($user);

            $manager->persist($task);
        }
        $manager->flush();
    }
}
