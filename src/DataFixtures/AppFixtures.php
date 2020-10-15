<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        //User management
        $users = [];
        for ($i = 1; $i <= 10; $i++) {
            $user = new User();
            $user->setUsername('anonyme');
            $user->setEmail("anonyme$i@gmail.com");
            $user->setPassword(null);

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
            $task->setUser($user);

            $manager->persist($task);
        }
        $manager->flush();
    }
}
