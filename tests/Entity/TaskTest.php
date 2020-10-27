<?php


namespace App\Tests\Entity;



use App\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaskTest extends KernelTestCase
{
    public function testValidEntity()
    {
        self::bootKernel();

        $task = new Task();
        $task->setTitle('titre de la task');
        $task->setContent('lorem ipsum');
        $task->setAuthor(null);



        $error = self::$container->get('validator')->validate($task);

        $this->assertCount(0, $error);

    }

    public function testNonValidEntity()
    {
        self::bootKernel();

        $task = new Task();
        $task->setTitle('titre de la task');
        $task->setContent('');
        $task->setAuthor(null);



        $error = self::$container->get('validator')->validate($task);

        $this->assertCount(1, $error);

    }

}