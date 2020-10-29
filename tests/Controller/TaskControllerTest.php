<?php

namespace Tests\App\Controller;


use App\Entity\Task;
use App\Tests\NeedLogin;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class TaskControllerTest extends WebTestCase
{
    use NeedLogin;
    use FixturesTrait;

    private $client = null;
    private $getEntityManager = null;

    public function setUp()
    {
        $this->client = self::createClient();

        $this->getEntityManager = $this->client->getContainer()->get('doctrine.orm.entity_manager');
    }

    public function testUserConnectedIsRequired()
    {


        $this->client->request('GET', '/tasks');

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

    }

    public function testRedirectIfNotConnected()
    {

        $this->client->request('GET', '/tasksDone');

        $this->assertResponseRedirects();

    }

    public function testCreateActionRedirectNotUser()
    {

        $this->client->request('GET', '/tasks/create');

        $crawler = $this->client->followRedirect();

        self::assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        self::assertSame(1, $crawler->filter('input[name="_username"]')->count());
        self::assertSame(1, $crawler->filter('input[name="_password"]')->count());

        self::assertSelectorTextContains('button', 'Se connecter');
    }

    public function testCreateActionTask()
    {

        $users = $this->loadFixtureFiles([dirname(__DIR__) . '\DataFixturesTest.yaml']);

        $this->login($this->client, $users['beniamin']);

        $crawler = $this->client->request('POST', '/tasks/create');

        $form = $crawler->selectButton('Ajouter')->form([
            'task[title]' => 'Test création tâche',
            'task[content]' => 'Contenu de la tâche test'
        ]);

        $this->client->submit($form);

        $crawler = $this->client->followRedirect();

        self::assertSame(1, $crawler->filter('.alert.alert-success')->count());

        self::assertGreaterThan(
            0,
            $crawler->filter('div:contains("La tâche a été bien été ajoutée.")')->count()
        );


        self::assertSame(1, $crawler->filter('html:contains("Test création tâche")')->count());
        self::assertSame(1, $crawler->filter('html:contains("Contenu de la tâche test")')->count());

        $this->getEntityManager = $this->client->getContainer()->get('doctrine.orm.entity_manager');
        $userTaskCreated = $this->getEntityManager->getRepository(Task::class)->findOneBy([
            'title' => 'Test création tâche'
        ]);
        $this->assertSame('beniamin', $userTaskCreated->getAuthor()->getUsername());


    }

    public function testEditAction()
    {
        $users = $this->loadFixtureFiles([dirname(__DIR__) . '\DataFixturesTest.yaml']);

        $this->login($this->client, $users['beniamin']);

        $crawler = $this->client->request('POST', '/tasks/1/edit');

        self::assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        self::assertSame(1, $crawler->filter('input[name="task[title]"]')->count());
        self::assertSame(1, $crawler->filter('textarea[name="task[content]"]')->count());

        $form = $crawler->selectButton('Modifier')->form([
            'task[title]' => 'Modification d\'une tâche',
            'task[content]' => 'Modifier le contenu de la tâche'
        ]);

        $this->client->submit($form);

        $crawler = $this->client->followRedirect();

        self::assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        self::assertSame(1, $crawler->filter('.alert.alert-success')->count());
        self::assertGreaterThan(
            1,
            $crawler->filter('div:contains("La tâche a bien été modifiée.")')->count()
        );

    }

    public function testToggleCompleted()
    {
        $users = $this->loadFixtureFiles([dirname(__DIR__) . '\DataFixturesTest.yaml']);

        $this->login($this->client, $users['beniamin']);

        $task =  $users['task1'];

        $task->setIsDone(0);

        $this->client->request('GET', 'tasks/2/toggle');


        $crawler = $this->client->followRedirect();


        self::assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());


        self::assertSame(1, $crawler->filter('div.alert-success:contains("marquée comme faite.")')->count());
    }

    public function testToggleInCompleted()
    {
        $users = $this->loadFixtureFiles([dirname(__DIR__) . '\DataFixturesTest.yaml']);

        $this->login($this->client, $users['beniamin']);

        $task =  $users['task2'];

        $task->setIsDone(1);


        $this->client->request('GET', 'tasks/2/toggle');


        $crawler = $this->client->followRedirect();


        self::assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());


        self::assertSame(1, $crawler->filter('div.alert-danger:contains("NON faite.")')->count());
    }

    public function testDeleteTaskAction()
    {
        $users = $this->loadFixtureFiles([dirname(__DIR__) . '\DataFixturesTest.yaml']);

        $this->login($this->client, $users['beniamin']);


        $this->client->request('GET', 'tasks/1/delete');

        $crawler = $this->client->followRedirect();

        self::assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $this->assertEquals(
            1,
            $crawler->filter('div.alert-success:contains("La tâche a bien été supprimée.")')->count()
        );
    }


}
