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
//        $this->login($client, $users['user']);

        $client->request('GET', '/users');

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

    }


    public function testCreateAction()
    {
        $client = static::createClient();
        $users = $this->loadFixtureFiles([dirname(__DIR__) . '\DataFixturesTest.yaml']);
        $this->login($client, $users['beniamin']);

        $crawler = $client->request('POST', '/users/create');


        $form = $crawler->selectButton('Ajouter')->form([
            'user[username]' => 'dsftest',
            'user[password][first]' => '123456',
            'user[password][second]' => '123456',
            'user[email]' => 'userdsf@tolan.me',
        ]);

        $client->submit($form);

        $crawler = $client->followRedirect();

        self::assertSame(1, $crawler->filter('.alert.alert-success')->count());

        self::assertGreaterThan(
            1,
            $crawler->filter('div:contains("L\'utilisateur a bien été ajouté.")')->count()
        );

        self::assertSame(1, $crawler->filter('html:contains("dsftest")')->count());
        self::assertSame(1, $crawler->filter('html:contains("userdsf@tolan.me")')->count());


    }

    public function testEditUserAction()
    {
        $client = static::createClient();
        $users = $this->loadFixtureFiles([dirname(__DIR__) . '\DataFixturesTest.yaml']);
        $this->login($client, $users['beniamin']);


        $user =  $users['user'];


        $crawler = $client->request('GET', '/users/'. $user->getId() .'/edit');


        self::assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());


        $form = $crawler->selectButton('Modifier')->form([
            'user[username]' => 'Test édition utilisateur',
            'user[password][first]' => 'password',
            'user[password][second]' => 'password',
            'user[email]' => 'useredit@email.com',
        ]);


        $client->submit($form);


        $crawler = $client->followRedirect();


        self::assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());


        self::assertSame(1, $crawler->filter('.alert.alert-success')->count());


        static::assertSelectorTextSame('div.alert', 'Superbe ! L\'utilisateur a bien été modifié');


        self::assertSame(1, $crawler->filter('html:contains("Test édition utilisateur")')->count());
        self::assertSame(1, $crawler->filter('html:contains("useredit@email.com")')->count());
    }

}
