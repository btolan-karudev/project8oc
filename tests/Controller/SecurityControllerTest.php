<?php

namespace Tests\App\Controller;

use App\Tests\NeedLogin;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class SecurityControllerTest extends WebTestCase
{
    use NeedLogin;
    use FixturesTrait;

    public function testDisplayLogin()
    {
        $client = static::createClient();

        $client->request('GET', '/login');

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains('button', 'Se connecter');
        $this->assertSelectorNotExists('.alert');

    }

    public function testLoginBadCredentials()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form([
            '_username' => 'beniamin@tolan.me',
            '_password' => 'passfake'
        ]);
        $client->submit($form);

        $this->assertResponseRedirects();
        $client->followRedirects();

    }

    public function testLoginOKCredentials()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form([
            '_username' => 'beniamin',
            '_password' => 'admin'
        ]);
        $client->submit($form);

        $this->assertResponseRedirects();


    }

    public function testLoginActionWithGoodCredentials()
    {
        $client = static::createClient();

        $client->request('POST', '/login', [
            '_username' => 'beniamin',
            '_password' => 'admin'
        ]);

        $crawler = $client->followRedirect();
        self::assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        self::assertSame(1, $crawler->filter('h1')->count());

    }

    public function testUserAccess()
    {
        $client = static::createClient();

        $users = $this->loadFixtureFiles([dirname(__DIR__) . '\DataFixturesTest.yaml']);
        $this->login($client, $users['user']);

        $client->request('GET', '/tasks');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        self::assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());

    }

    public function testLogoutLink()
    {
        $client = static::createClient();

        $users = $this->loadFixtureFiles([dirname(__DIR__) . '\DataFixturesTest.yaml']);
        $this->login($client, $users['user']);


        $crawler = $client->request('GET', '/');

        $link = $crawler->selectLink('Se déconnecter')->link();
        $client->click($link);
        $crawler = $client->followRedirect();
        self::assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        self::assertSame(1, $crawler->filter('input[name="_username"]')->count());
        self::assertSame(1, $crawler->filter('input[name="_password"]')->count());
        self::assertSelectorTextContains('button', 'Se connecter');
    }

    public function testLogoutPath()
    {
        $client = static::createClient();

        $users = $this->loadFixtureFiles([dirname(__DIR__) . '\DataFixturesTest.yaml']);
        $this->login($client, $users['user']);

        $client->request('GET', '/logout');
        $crawler = $client->followRedirect();
        $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        self::assertSame(1, $crawler->filter('input[name="_username"]')->count());
        self::assertSame(1, $crawler->filter('input[name="_password"]')->count());
        self::assertSelectorTextContains('button', 'Se connecter');
    }



}
