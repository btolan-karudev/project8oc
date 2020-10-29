<?php


namespace Tests\App\Entity;



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


    public function testDoneCreated()
    {
        $task = new Task();

        $this->assertFalse($task->isDone());
        $this->assertInstanceOf('DateTime', $task->getCreatedAt());
    }

    public function testGetDoneCreated()
    {
        $task = new Task();

        $this->assertFalse($task->getIsDone());
        $this->assertInstanceOf('DateTime', $task->getCreatedAt());
    }

}