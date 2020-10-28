<?php

namespace Tests\App\Controller;


use App\Entity\Role;
use App\Entity\User;
use App\Tests\NeedLogin;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class UserControllerTest extends WebTestCase
{
    use NeedLogin;
    use FixturesTrait;

    public function testUserConnectedIsRequired()
    {
        $client = static::createClient();
        $users = $this->loadFixtureFiles([dirname(__DIR__) . '\DataFixturesTest.yaml']);
        $this->login($client, $users['beniamin']);

        $client->request('GET', '/users');

        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);

    }


    public function testCreateAction()
    {
        $client = static::createClient();
//        $users = $this->loadFixtureFiles([dirname(__DIR__) . '\DataFixturesTest.yaml']);
//        $this->login($client, $users['beniamin']);
//
        $userRole = new Role();
        $userRole->setTitle('ROLE_ADMIN');

        $user = new User();
        $user->addUserRole($userRole)
            ->setUsername('testadmin')
            ->setPassword('123456')
            ->setEmail('email@dd.com');


        $crawler = $client->request('POST', '/users/create');
        var_dump($crawler);
        $form = $crawler->selectButton('Ajouter')->form();
        var_dump($form);
        $crawler = $client->submit($form, [
            'user[username]' => 'dsftest',
            'user[password][first]' => '123456',
            'user[password][second]' => '123456',
            'user[email]' => 'userdsf@tolan.me'
        ]);

//        $form = $crawler->selectButton('submit')->form([
//            'user[username]' => 'dsftest',
//            'user[password][first]' => '123456',
//            'user[password][second]' => '123456',
//            'user[email]' => 'userdsf@tolan.me'
//        ]);

//        $client->submit($form);

//        $crawler = $client->request('GET', '/users');

//        self::assertSame(0, $crawler->filter('.alert.alert-success')->count());
//
//        self::assertGreaterThan(
//            -1,
//            $crawler->filter('div:contains("La tâche a été bien été ajoutée.")')->count()
//        );
//
//        self::assertSame(0, $crawler->filter('html:contains("Test création d\'une tâche")')->count());
//        self::assertSame(0, $crawler->filter('html:contains("Contenu de la tâche test")')->count());

//        $this->getEntityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
//        $userTaskCreated = $this->getEntityManager->getRepository(Task::class)->findOneBy([
//            'title' => 'Test création d\'une tâche'
//        ]);

    }

}
