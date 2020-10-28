<?php

namespace Tests\App\Controller;


use App\Tests\NeedLogin;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class TaskControllerTest extends WebTestCase
{
    use NeedLogin;
    use FixturesTrait;

    public function testUserConnectedIsRequired()
    {
        $client = static::createClient();

        $client->request('GET', '/tasks');

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

    }

    public function testRedirectIfNotConnected()
    {
        $client = static::createClient();

        $client->request('GET', '/tasksDone');

        $this->assertResponseRedirects();

    }

    public function testCreateActionRedirectNotUser()
    {
        $client = static::createClient();

        $client->request('GET', '/tasks/create');

        $crawler = $client->followRedirect();

        self::assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        self::assertSame(1, $crawler->filter('input[name="_username"]')->count());
        self::assertSame(1, $crawler->filter('input[name="_password"]')->count());

        self::assertSelectorTextContains('button', 'Se connecter');
    }

    public function testCreateAction()
    {
        $client = static::createClient();
        $users = $this->loadFixtureFiles([dirname(__DIR__) . '\DataFixturesTest.yaml']);
        $this->login($client, $users['user']);

        $crawler = $client->request('GET', '/tasks/create');

        $form = $crawler->selectButton('Ajouter')->form([
            'task[title]' => 'Test création tâche',
            'task[content]' => 'Contenu de la tâche test'
        ]);

        $client->submit($form);

        $crawler = $client->request('GET', '/tasks');

        self::assertSame(0, $crawler->filter('.alert.alert-success')->count());

        self::assertGreaterThan(
            -1,
            $crawler->filter('div:contains("La tâche a été bien été ajoutée.")')->count()
        );

        self::assertSame(0, $crawler->filter('html:contains("Test création d\'une tâche")')->count());
        self::assertSame(0, $crawler->filter('html:contains("Contenu de la tâche test")')->count());

//        $this->getEntityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
//        $userTaskCreated = $this->getEntityManager->getRepository(Task::class)->findOneBy([
//            'title' => 'Test création d\'une tâche'
//        ]);

    }

}
