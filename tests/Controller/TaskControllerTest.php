<?php

namespace Tests\App\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class TaskControllerTest extends WebTestCase
{
    public function testUserConnectedIsRequired()
    {
        $client = static::createClient();

        $client->request('GET', '/tasks');

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

    }

    public function testRedirectIfNotConnected()
    {
        $client = static::createClient();

        $client->request('GET', '/tasks');

        $this->assertResponseRedirects();

    }

}
