<?php

namespace App\DataFixtures;

use App\Entity\Task;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i=1; $i <=100; $i++) {
            $task = new Task();
            $task->setTitle("Titre n°$i");
            $task->setCreatedAt(new \DateTime());
            $task->setContent("<p>Je suis le contendu de cette action</p>");

            $manager->persist($task);
        }
        $manager->flush();
    }
}
